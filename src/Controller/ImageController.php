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

    /**
     * This route is used to delete images in DB
     * @param Request $request,
     * @param ImagesRepository $imagesRepository
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/image/suppression', name: 'image.delete', methods: ['DELETE'])]
    public function deleteImage(
        Request $request,
        ImagesRepository $imagesRepository,
        EntityManagerInterface $manager,
    ): JsonResponse {
        $image = $imagesRepository->find($request->request->get("imageId"));

        //Return acess denies when image do not exist
        if (!$image) {
            throw new AccessDeniedHttpException("Image deletion Access denied");
        }

        if ($image->getUser() != $this->getUser()) {
            throw new AccessDeniedHttpException("Image deletion Access denied");
        }

        $manager->remove($image);
        $manager->flush();
        return new JsonResponse(['success' => true], 200);
    }
}