<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * This controller allow us to edit user profile
     *
     * @param User $selectedUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === selectedUser")]
    #[Route('/utilisateur/edition/{id}', name: 'user.edit', methods: ['GET', 'POST'])]
    public function index(
        User                        $selectedUser,
        Request                     $request,
        EntityManagerInterface      $manager,
        UserPasswordHasherInterface $hasher
    ): Response
    {
        $form = $this->createForm(UserType::class, $selectedUser);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($selectedUser, $form->getData()->getPlainPassword())) {
                $selectedUser = $form->getData();
                $manager->persist($selectedUser);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre profil a été modifié avec succès'
                );
                return $this->redirectToRoute('home');
            }
            $this->addFlash(
                'warning',
                'Le mot de passe renseigné est incorrecte'
            );
        }


        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * this route allow us to change password
     *
     * @param User $selectedUser
     * @param Request $request
     * @param UserPasswordHasherInterface $hasher
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === selectedUser")]
    #[Route('/utilisateur/edition-mot-de-passe/{id}', 'user.edit.password', methods: ['GET', 'POST'])]
    public function editPassword(
        User                        $selectedUser,
        Request                     $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface      $manager
    ): Response
    {
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($selectedUser, $form->getData()['plainPassword'])) {
                $selectedUser->setUpdatedAt(new \DateTimeImmutable());
                $selectedUser->setPlainPassword(
                    $form->getData()['newPassword']
                );
                $manager->persist($selectedUser);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a été modifié avec succès'
                );
                return $this->redirectToRoute('home');
            }
            $this->addFlash(
                'warning',
                'Le mot de passe renseigné est incorrecte'
            );
        }


        return $this->render('pages/user/edit_password.html.twig', [
                'form' => $form->createView()
            ]
        );
    }
}
