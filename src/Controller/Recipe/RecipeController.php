<?php
declare(strict_types=1);

namespace App\Controller\Recipe;

use App\Entity\Images;
use App\Entity\Mark;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Form\MarkType;
use App\Form\RecipeType;
use App\Repository\MarkRepository;
use App\Repository\RecipeRepository;
use App\Service\PictureService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Cache\ItemInterface;

class RecipeController extends AbstractController
{
    /**
     * This route display all user recipes
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
    ): Response {
        $user = $this->getUser();
        $recipes = $paginator->paginate(
            $recipeRepository->findUserRecipesrQuery(null, $user),
            /* query NOT result */
            $request->query->getInt('page', 1),
            /*page number*/
            12 /*limit per page*/
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    /**
     * This route display a randow recipe via recipe.show route
     * @param RecipeRepository $recipeRepository
     * @return Response
     */
    #[Route('/recette/random', 'recipe.random', methods: ['GET'])]
    public function randomRecipe(
        RecipeRepository $recipeRepository
    ): Response {
        $randomRecipe = $recipeRepository->findRandomPublicRecipe();
        return $this->redirectToRoute('recipe.show', ['id' => $randomRecipe->getId()]);
    }

    /**
     * This route display recipes visible by public
     * @param RecipeRepository $recipeRepository
     * @param PaginatorInterface $paginator,
     * @param Request $request
     * @return Response
     */
    #[Route('/recette/publique', 'recipe.index.public', methods: ['GET'])]
    public function publicRecipes(
        RecipeRepository $recipeRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $query = $recipeRepository->findPublicRecipeQuery(null);

        $recipes = $paginator->paginate(
            $query,
            /* query NOT result */
            $request->query->getInt('page', 1),
            /*page number*/
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
        EntityManagerInterface $manager,
        PictureService $pictureService
    ): Response {

        $recipe = new Recipe();
        //adding RecipeIngredient in order to display a field in form
        $recipe->addRecipeIngredient(new RecipeIngredient());

        $recipe->setUser($this->getUser());

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            // On récupère les images
            $image = $form->get('images')->getData();

            if ($image != null) {
                // On appelle le service d'ajout d'image
                $fichier = $pictureService->add($image, Recipe::PICTURE_DIRECTORY, Recipe::PICTURE_SIZE_WIDTH, Recipe::PICTURE_SIZE_HEIGHT);

                $img = new Images();
                $img->setName($fichier)
                ->setUser($this->getUser())
                ->setPictureDirectory(Recipe::PICTURE_DIRECTORY)
                ->setPictureWidth(Recipe::PICTURE_SIZE_WIDTH)
                ->setPictureHeight(Recipe::PICTURE_SIZE_HEIGHT);

                $recipe->addImage($img);
            }

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
    #[IsGranted('ROLE_USER')]
    public function edit(
        Request $request,
        EntityManagerInterface $manager,
        ?Recipe $recipe,
        PictureService $pictureService
    ): Response {

        // If there is no recipe dening access
        if (!$recipe) {
            throw new AccessDeniedHttpException("Edit Access denied");
        }

        // Security Control
        if ($this->getUser() != $recipe->getUser()) {
            throw new AccessDeniedHttpException("Edit Access denied");
        }

        $originalRecipeIngredients = new ArrayCollection();
        $originalRecipe = clone $recipe;

        // dynamic recipeingredient - Create an ArrayCollection of the current recipeIngredient objects in the database for this recipe
        // Probably related to Lazy loading of recipe entity
        foreach ($recipe->getRecipeIngredients() as $recipeIngredient) {
            $originalRecipeIngredients->add($recipeIngredient);
        }

        $form = $this->createForm(RecipeType::class, $recipe,['edit_mode' => true]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newRecipe = $form->getData();

            // dynamic recipeingredient - checking if some are removed in the form
            foreach ($originalRecipeIngredients as $recipeIngredient) {
                if (false === $newRecipe->getRecipeIngredients()->contains($recipeIngredient)) {
                    // dynamic recipeingredient - if it's the case removing them from db
                    $manager->remove($recipeIngredient);
                }
            }

            // On définit un booléen pour déterminer d'autre facteur de modification de la recette
            $isRecipeMustBeDissaproved = false;

            // On récupère les images
            $image = $form->get('images')->getData();

            if ($image != null) {
                // On appelle le service d'ajout
                $fichier = $pictureService->add($image, Recipe::PICTURE_DIRECTORY, Recipe::PICTURE_SIZE_WIDTH, Recipe::PICTURE_SIZE_HEIGHT);

                $img = new Images();
                $img->setName($fichier)
                ->setUser($this->getUser())
                ->setPictureDirectory(Recipe::PICTURE_DIRECTORY)
                ->setPictureWidth(Recipe::PICTURE_SIZE_WIDTH)
                ->setPictureHeight(Recipe::PICTURE_SIZE_HEIGHT);

                $newRecipe->addImage($img);
                //Set to true because adding 
                $isRecipeMustBeDissaproved = true;
            }

            //reset status in order to not show inapropriate content
            if (Recipe::isModificationThatRequireStatusReset($originalRecipe, $newRecipe, $isRecipeMustBeDissaproved)) {
                $newRecipe->statusResetAfterModification();
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
            'form' => $form->createView(),
            'recipe' => $recipe
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
    #[IsGranted('ROLE_USER')]
    public function delete(
        ?Recipe $recipe,
        EntityManagerInterface $entityManager
    ): Response {

        // If there is no recipe dening access
        if (!$recipe) {
            throw new AccessDeniedHttpException("Delete Access denied");
        }

        // Security Control
        if ($this->getUser() != $recipe->getUser()) {
            throw new AccessDeniedHttpException("Delete Access denied");
        }

        $entityManager->remove($recipe);
        $entityManager->flush();
        $this->addFlash(
            'success',
            sprintf('La recette %s a été supprimé avec succès', $recipe->getName())
        );

        return $this->redirectToRoute('recipe.index');
    }

    /**
     * This controller allow access to a public recipe
     *
     * @param Recipe $recipe
     * @return Response
     */
    #[Route('/recette/{id}', 'recipe.show', methods: ['GET', 'POST'])]
    public function show(
        ?Recipe $recipe,
        Request $request,
        MarkRepository $markRepository,
        EntityManagerInterface $manager
    ): Response {

        // If there is no recipe dening access
        if (!$recipe) {
            throw new AccessDeniedHttpException("View Access denied");
        }

        // Si ce n'est pas l'auteur et que la recette n'est pas sensé être accessible au public
        if (!$recipe->isAccessibleByPublic() && $this->getUser() !== $recipe->getUser() && !in_array('ROLE_ADMIN',$this->getUser()->getRoles())) {
            throw new AccessDeniedHttpException("View Access denied");
        }

        // récupération des notes associées
        $relatedMarks = $markRepository->findBy([
            'recipe' => $recipe
        ]);

        // Si ce sont des personnes ne pouvant pas noter cette recette
        if ($this->getUser() === $recipe->getUser() or $this->getUser() === null) {
            return $this->render('pages/recipe/show.html.twig', [
                'recipe' => $recipe,
                'relatedMarks' => $relatedMarks
            ]);
        }

        $mark = new Mark();

        $form = $this->createForm(MarkType::class, $mark);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mark = $form->getData();

            $existingMark = $markRepository->findOneBy([
                'user' => $this->getUser(),
                'recipe' => $recipe
            ]);

            if ($existingMark) {
                $this->addFlash(
                    'success',
                    sprintf('La note pour %s a bien été modifiée', $recipe->getName())
                );
                $existingMark->setMark($mark->getMark());
                $existingMark->setComment($mark->getComment());
                $existingMark->setCreatedAt(new \DateTimeImmutable());
            } else {
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
    #[IsGranted('ROLE_USER')]
    #[Route('/recette/{id}/start-check', 'recipe.start-check', methods: ['GET'])]
    public function startCheck(
        ?Recipe $recipe,
        EntityManagerInterface $manager
    ): Response {

        // If there is no recipe dening access
        if (!$recipe) {
            throw new AccessDeniedHttpException("Start-check Access denied");
        }
        
        // Security Control
        if ($this->getUser() != $recipe->getUser()) {
            throw new AccessDeniedHttpException("Start-check Access denied");
        }

        if ($recipe->getStatus() !== Recipe::STATUS_NOT_APPROVED) {
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