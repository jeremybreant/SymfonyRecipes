<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                    'placeholder' => 'Jhon Doe'
                ],
                'label' => 'Nom / Prenom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                    'placeholder'=> 'Pseudonyme'
                ],
                'required' => false,
                'label' => 'Pseudo',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('plainPassword',PasswordType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '*************'
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'label' => 'Mot de passe',
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Modifier'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
