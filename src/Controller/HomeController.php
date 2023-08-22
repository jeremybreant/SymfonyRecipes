<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     * @return Response
     */
    public function index(
        RecipeRepository $recipeRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $query = $recipeRepository->findPublicRecipeQuery(null);

        $recipes = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('pages/index.html.twig',[
            'recipes' => $recipes
        ]);
    }
}