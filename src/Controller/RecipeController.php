<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    /**
     * This route iss to display all recipes
     *
     * @param RecipeRepository $recipeRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/recipe', name: 'recipe.index', methods: ['GET'])]
    public function index(
        RecipeRepository $recipeRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $recipes = $paginator->paginate(
            $recipeRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

}