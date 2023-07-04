<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Entity\Recipe;
use App\Form\MarkType;
use App\Repository\MarkRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RecipeFavController extends AbstractController
{

    #[IsGranted('ROLE_USER')]
    #[Route('/toggle-recipe-fav', name: 'recipefav.toggle', methods: ['POST'])]
    public function toggle(
        Request $request,
        RecipeRepository $recipeRepository,
        EntityManagerInterface $manager
    ): JsonResponse
    {
        $cache = new FilesystemAdapter();

        // Créer un tableau de données
        $data = [
            'id' => $request->request->get("recipeId"),
            'status' => 'success'
        ];

        // Retourner une JsonResponse

        //*
        $recipe = $recipeRepository->find($request->request->get("recipeId"));

        $user = $this->getUser();
        $user->toggleFavoriteRecipe($recipe);
        $cache->delete('recipes');
        $manager->persist($user);
        $manager->flush();
        //*/
        return $this->json($data);
    }
}