<?php
declare(strict_types=1);

namespace App\Controller\Recipe;

use App\Entity\Mark;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Form\MarkType;
use App\Form\RecipeType;
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

class RecipeController extends AbstractController
{
    /**
     * This route iss to display all recipes
     *
     * @param RecipeRepository $recipeRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/recette', name: 'recipe.index', methods: ['GET'])]
    public function index(
        RecipeRepository $recipeRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $recipes = $paginator->paginate(
            $recipeRepository->findBy(['user' => $this->getUser()]), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('/recette/random', 'recipe.random', methods: ['GET'])]
    public function randomRecipe(
        EntityManagerInterface $manager,
        RecipeRepository $recipeRepository,
        Request $request
    ): Response
    {
        $cache = new FilesystemAdapter();
        $data = $cache->get('recipes', function (ItemInterface $item) use ($recipeRepository){
            $item->expiresAfter(300);

            /** @var Recipe[] */
            $recipes = $recipeRepository->findPublicRecipe(null);

            // Force loading of mark collection before caching (because fetch="LAZY" by default)
            foreach ($recipes as $recipe) {
                $recipe->getMarks()->toArray();
                $recipe->getCategories()->toArray();
            }

            return $recipes;
        });

        $randomRecipe = $data[rand(0, count($data)-1)];
        return $this->redirectToRoute('recipe.show', ['id' => $randomRecipe->getId()]);
    }

    #[Route('/recette/publique', 'recipe.index.public', methods: ['GET'])]
    public function publicRecipes(
        RecipeRepository $recipeRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $cache = new FilesystemAdapter();
        $data = $cache->get('recipes', function (ItemInterface $item) use ($recipeRepository){
            $item->expiresAfter(300);

            /** @var Recipe[] */
            $recipes = $recipeRepository->findPublicRecipe(null);

            // Force loading of mark collection before caching (because fetch="LAZY" by default)
            foreach ($recipes as $recipe) {
                $recipe->getMarks()->toArray();
                $recipe->getCategories()->toArray();
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

    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/recette/creation', 'recipe.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ) : Response
    {
        $recipe = new Recipe();
        $recipe->addRecipeIngredient(new RecipeIngredient());

        $recipe->setUser($this->getUser());

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $recipe = $form->getData();

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été créé avec succès'
            );

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This route is used to edit a recipe
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Recipe $recipe
     * @return Response
     */
    #[Route('/recette/edit/{id}', 'recipe.edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user === recipe.getUser()")]
    public function edit(
        Request $request,
        EntityManagerInterface $manager,
        Recipe $recipe
    ):Response
    {
        $originalRecipeIngredients = new ArrayCollection();
        $originalRecipe = clone $recipe;

        // dynamic recipeingredient - Create an ArrayCollection of the current recipeIngredient objects in the database for this recipe
        foreach ($recipe->getRecipeIngredients() as $recipeIngredient) {
            $originalRecipeIngredients->add($recipeIngredient);
        }

        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $newRecipe = $form->getData();

            // dynamic recipeingredient - checking if some are removed in the form
            foreach ($originalRecipeIngredients as $recipeIngredient) {
                if (false === $newRecipe->getRecipeIngredients()->contains($recipeIngredient)) {
                    // dynamic recipeingredient - if it's the case removing them from db
                    $manager->remove($recipeIngredient);
                }
            }
            //reset status in order to not show inapropriate content
            if($originalRecipe != $newRecipe)
            {
                $newRecipe->setStatus(Recipe::STATUS_NOT_APPROVED);
            }

            $manager->persist($newRecipe);
            $manager->flush();

            $this->addFlash(
                'success',
                sprintf('Votre recette %s a été modifié avec succès', $newRecipe->getName())
            );

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This route is used to delete recipe
     *
     * @param Recipe $recipe
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/recette/suppression/{id}', 'recipe.delete', methods: ['GET'])]
    #[Security("is_granted('ROLE_USER') and user === recipe.getUser()")]
    public function delete(
        Recipe $recipe,
        EntityManagerInterface $entityManager
    ) : Response
    {
        if(!$recipe)
        {
            $this->addFlash(
                'danger',
                'La recette n\'a pas été trouvé'
            );
        }
        else
        {
            $entityManager->remove($recipe);
            $entityManager->flush();

            $this->addFlash(
                'success',
                sprintf('La recette %s a été supprimé avec succès', $recipe->getName())
            );
        }

        return $this->redirectToRoute('recipe.index');
    }

    /**
     * This controller allow access to a public recipe
     *
     * @param Recipe $recipe
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and (recipe.getIsPublic() === true || user === recipe.getUser())")]
    #[Route('/recette/{id}', 'recipe.show', methods: ['GET','POST'])]
    public function show(
        Recipe $recipe,
        Request $request,
        MarkRepository $markRepository,
        EntityManagerInterface $manager
    ) : Response
    {

        if($request->getUser() === $recipe->getUser())
        {
            return $this->render('pages/recipe/show.html.twig', [
                'recipe' => $recipe
            ]);
        }

        $relatedMarks = $markRepository->findBy([
            'recipe' => $recipe
        ]);

        $mark = new Mark();

        $form = $this->createForm(MarkType::class, $mark);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $mark = $form->getData();

            $existingMark = $markRepository->findOneBy([
                'user' => $this->getUser(),
                'recipe' => $recipe
            ]);

            if($existingMark){
                $this->addFlash(
                    'success',
                    sprintf('La note pour %s a bien été modifiée', $recipe->getName())
                );
                $existingMark->setMark($mark->getMark());
                $existingMark->setComment($mark->getComment());
                $existingMark->setCreatedAt(New \DateTimeImmutable());
            }
            else {
                $this->addFlash(
                    'success',
                    'Votre note a bien été prise en compte'
                );
                $mark->setUser($this->getUser())
                    ->setRecipe($recipe);
                $manager->persist($mark);
            }
            $manager->flush();



            return $this->redirectToRoute('recipe.index.public');
        }
        return $this->render('pages/recipe/show.html.twig', [
            'recipe' => $recipe,
            'relatedMarks' => $relatedMarks,
            'form' => $form->createView()
        ]);
    }

    /**
     * This controller allow access to a public recipe
     *
     * @param Recipe $recipe
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === recipe.getUser()")]
    #[Route('/recette/{id}/start-check', 'recipe.start-check', methods: ['GET'])]
    public function startCheck(
        Recipe $recipe,
        EntityManagerInterface $manager
    ) : Response
    {
        if($recipe->getStatus() !== Recipe::STATUS_NOT_APPROVED){
            
            return $this->redirectToRoute('recipe.index');
        }

        $recipe->setStatus(Recipe::STATUS_IN_APPROBATION);
        $manager->persist($recipe);
        $manager->flush();

        $this->addFlash(
            'success',
            sprintf('La recette %s est désormais en cours approbation', $recipe->getName())
        );
        
        return $this->redirectToRoute('recipe.index');
    }
}