<?php
declare(strict_types=1);

namespace App\Controller\Recipe;


use App\Entity\User;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RecipeFavController extends AbstractController
{
    /**
     * This route iss to display all recipes
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recette/favoris', name: 'recipe.favorite', methods: ['GET'], priority: 1)]
    public function search(
        PaginatorInterface $paginator,
        RecipeRepository $recipeRepository,
        Request $request,
    ): Response {

        $user = $this->getUser();

        $recipes = $paginator->paginate(
            $recipeRepository->findRecipesLikedByTheUserQuery(null, $user),
            /*$recipeRepository->findPublicRecipe(null), /* query NOT result */
            $request->query->getInt('page', 1),
            /*page number*/
            12, /*limit per page*/
        );

        $totalCount = count($recipes);

        return $this->render('pages/recipe/index_favorite.html.twig', [
            'recipes' => $recipes,
            'totalCount' => $totalCount
        ]);
    }

    /**
     * This route is used to toggle add or remove a recipe from a user's favorite
     * @param Request $request,
     * @param RecipeRepository $recipeRepository
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/toggle-recipe-fav', name: 'recipefav.toggle', methods: ['POST'])]
    public function toggle(
        Request $request,
        RecipeRepository $recipeRepository,
        EntityManagerInterface $manager
    ): JsonResponse {
        $cache = new FilesystemAdapter();

        // Créer un tableau de données
        $data = [
            'id' => $request->request->get("recipeId"),
            'status' => 'success'
        ];

        // Retourner une JsonResponse

        //retrieve recipe
        $recipe = $recipeRepository->find($request->request->get("recipeId"));

        //If the recipe to add in fav doesn't exist 
        if(!$recipe){
            throw new AccessDeniedHttpException("Recipe Access denied");
        }

        /** @var User */
        $user = $this->getUser();
        $user->toggleFavoriteRecipe($recipe);
        $cache->delete('recipes');
        $manager->persist($user);
        $manager->flush();
        //*/
        return $this->json($data);
    }
}