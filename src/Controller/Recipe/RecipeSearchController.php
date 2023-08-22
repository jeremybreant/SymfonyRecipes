<?php
declare(strict_types=1);

namespace App\Controller\Recipe;


use App\Form\SearchType;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
/* This should be reworked in order to not use it */
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeSearchController extends AbstractController
{
    /**
     * This route iss to display all recipes
     *
     * @param RecipeRepository $recipeRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recette/recherche', name: 'recipe.search', methods: ['GET'], priority: 1)]
    public function search(
        RecipeRepository $recipeRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $keyword = $request->query->get('keyword');
        $recipes = $paginator->paginate(
            $query = $recipeRepository->findRecipesBasedOnNameQuery(null, $keyword),
            /*$recipeRepository->findPublicRecipe(null), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );
        
        $totalCountQuery = $recipeRepository->findRecipesBasedOnNameQuery(null, $keyword);
        $totalCount = count($totalCountQuery->getScalarResult());

        return $this->render('pages/recipe/index_search.html.twig', [
            'recipes' => $recipes,
            'keyword' => $keyword,
            'totalCount' => $totalCount
        ]);
    }
}