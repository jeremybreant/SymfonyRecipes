<?php
declare(strict_types=1);

namespace App\Controller\Recipe;

use App\DataFixtures\CategoryFixture;
use App\Entity\Mark;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Form\MarkType;
use App\Form\RecipeType;
use App\Repository\CategoryRepository;
use App\Repository\MarkRepository;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
/* This should be reworked in order to not use it */
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Cache\ItemInterface;

class RecipeByCategoryController extends AbstractController
{
    #[Route('/recette/category/{categorySlug}', 'recipe.by.category', methods: ['GET'])]
    public function publicRecipesByCategories(
        string $categorySlug,
        CategoryRepository $categoryRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $cache = new FilesystemAdapter();
        $data = $cache->get('category-'.$categorySlug, function (ItemInterface $item) use ($categoryRepository, $categorySlug){
            $item->expiresAfter(15);

            $category = $categoryRepository->findOneBySlug($categorySlug);

            $recipes = $category->getRecipes();

            // Force loading of mark collection before caching (because fetch="LAZY" by default)
            foreach ($recipes as $recipe) {
                $recipe->getMarks()->toArray();
            }

            return $recipes;
        });

        $recipes = $paginator->paginate(
            $data,
            /*$recipeRepository->findPublicRecipe(null), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );

        return $this->render('pages/recipe/index_public.html.twig', [
            'recipes' => $recipes
        ]);
    }
}