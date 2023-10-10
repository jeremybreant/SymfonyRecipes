<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\ImagesRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class ImageController extends AbstractController
{

    #[IsGranted('ROLE_USER')]
    #[Route('/image/suppression', name: 'image.delete', methods: ['DELETE'])]
    public function deleteImage(Request $request, ImagesRepository $imagesRepository, EntityManagerInterface $manager, PictureService $pictureService): JsonResponse
    {
        $image = $imagesRepository->find($request->request->get("imageId"));
        
        if($image->getUser() != $this->getUser()){
            throw new AccessDeniedHttpException("Image deletion Access denied");
        }
        
        $folder = $request->request->get("folder");
        // On récupère le nom de l'image
        $filename = $image->getName();

        $path = $this->getParameter('images_directory');
        if(file_exists($path.$filename))
        {
            if(!$pictureService->delete($filename, $folder, 300, 300))
            {
                // La suppression a échoué
                return new JsonResponse(['error' => 'Erreur de suppression'], 400);
            }
        }
        $manager->remove($image);
        $manager->flush();
        return new JsonResponse(['success' => true], 200);      
    }
}