<?php
declare(strict_types=1);

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, string $folder, int $width, int $height)
    {
        // On donne un nouveau nom à l'image
        $file = md5(uniqId()) . '.webp';

        // On récupère le chemin de l'image
        $picture_path = $picture->getPathName();

        // On récupère les infos de l'image
        $picture_infos = getimagesize($picture_path);

        if ($picture_infos === false) {
            throw new Exception('Format d\'image invalide');
        }
        
        // On vérifie le format de l'image
        switch ($picture_infos['mime']) {
            case 'image/png':
                $picture_source = imagecreatefrompng($picture_path);
                break;
            case 'image/jpeg':
                $picture_source = imagecreatefromjpeg($picture_path);
                break;
            case 'image/wbp':
                $picture_source = imagecreatefromwebp($picture_path);
                break;
            default:
                throw new Exception('Format d\'image incorrecte');
        }

        // On recadre l'image
        // on récupère les dimensions
        $imageWidth = $picture_infos[0];
        $imageHeight = $picture_infos[1];

        // On vérifie l'orientation
        switch ($imageWidth <=> $imageHeight) {
            case -1: // portrait
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = (int) floor(($imageHeight - $squareSize) / 2);
                break;
            case 0: // carré
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;
                break;
            case 1: // paysage
                $squareSize = $imageHeight;
                $src_x = (int) floor(($imageWidth - $squareSize) / 2);
                $src_y = 0;
                break;
        }

        // On crée une nouvelle image vierge
        $resized_picutre = imagecreatetruecolor($width, $height);
        imagecopyresampled($resized_picutre, $picture_source, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);

        $path = $this->params->get('images_path') . $folder;

        // On crée le dossier de destination s'il n'existe pas
        $sizePath = $path . $width . 'x' . $height . '/';
        if (!file_exists($sizePath)) {
            mkdir($sizePath, 0755, true);
        }

        // On stocke l'image recadrée
        imagewebp($resized_picutre, $sizePath . $file);
        $picture->move($path, $file);

        return $file;
    }

    public function delete(string $file, string $folder, int $width, int $height)
    {
        if ($file !== 'default.webp') {

            $success = false;
            $path = $this->params->get('images_path') . $folder;

            $sizePath = $path . $width . 'x' . $height . '/';
            $pictureFilePath = $sizePath . $file;

            if(file_exists($pictureFilePath)){
                unlink($pictureFilePath);
                $success = true;
            }

            $original = $path . $file;

            if(file_exists($original)){
                unlink($original);
                $success = true;
            }
            return $success;
        }
        return false;
    }
}