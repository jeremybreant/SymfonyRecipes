<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\ImagesRepository;
use App\Repository\IngredientRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
/* This should be reworked in order to not use it */
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IngredientController extends AbstractController
{
    /**
     * This route is used to display all ingredients
     *
     * @param IngredientRepository $ingredientRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(
        IngredientRepository $ingredientRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $user = $this->getUser();
        $ingredients = $paginator->paginate(
            $ingredientRepository->findUserIngredientsrQuery(null, $user), /* query NOT result */
            $request->query->getInt('page', 1),
            /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    /**
     * This route is used to create a new ingredient
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/nouveau', 'ingredient.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
        PictureService $pictureService
    ): Response {
        $ingredient = new Ingredient();
        $ingredient->setUser($this->getUser());
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            // On récupère les images
            $image = $form->get('images')->getData();

            if ($image != null) {
                $fichier = $pictureService->add($image, Ingredient::PICTURE_DIRECTORY, Ingredient::PICTURE_SIZE_WIDTH, Ingredient::PICTURE_SIZE_HEIGHT);

                $img = new Images();
                $img->setName($fichier)
                ->setUser($this->getUser())
                ->setPictureDirectory(Ingredient::PICTURE_DIRECTORY)
                ->setPictureWidth(Ingredient::PICTURE_SIZE_WIDTH)
                ->setPictureHeight(Ingredient::PICTURE_SIZE_HEIGHT);

                $ingredient->addImage($img);
            }

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été créé avec succès'
            );

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This route is used to edit an ingredient
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Ingredient $ingredient
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === ingredient.getUser()")]
    #[Route('/ingredient/edit/{id}', 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        EntityManagerInterface $manager,
        Ingredient $ingredient,
        PictureService $pictureService
    ): Response {
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            // On récupère les images
            $image = $form->get('images')->getData();

            if ($image != null) {
                $fichier = $pictureService->add($image, Ingredient::PICTURE_DIRECTORY, Ingredient::PICTURE_SIZE_WIDTH, Ingredient::PICTURE_SIZE_HEIGHT);

                $img = new Images();
                $img->setName($fichier)
                ->setUser($this->getUser())
                ->setPictureDirectory(Ingredient::PICTURE_DIRECTORY)
                ->setPictureWidth(Ingredient::PICTURE_SIZE_WIDTH)
                ->setPictureHeight(Ingredient::PICTURE_SIZE_HEIGHT);

                $ingredient->addImage($img);
            }

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                sprintf('Votre ingrédient %s a été modifié avec succès', $ingredient->getName())
            );

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView(),
            'ingredient' => $ingredient
        ]);
    }

    /**
     * This route is used to delete an ingredient
     *
     * @param Ingredient $ingredient
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === ingredient.getUser()")]
    #[Route('/ingredient/suppression/{id}', 'ingredient.delete', methods: ['GET'])]
    public function delete(
        Ingredient $ingredient,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$ingredient) {
            $this->addFlash(
                'danger',
                'L\'ingrédient n\'a pas été trouvé'
            );
        } else {
            $entityManager->remove($ingredient);
            $entityManager->flush();

            $this->addFlash(
                'success',
                sprintf('Ingrédient %s a été supprimé avec succès', $ingredient->getName())
            );
        }

        return $this->redirectToRoute('ingredient.index');
    }
}