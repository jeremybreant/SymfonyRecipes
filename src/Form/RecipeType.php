<?php

namespace App\Form;

use App\Entity\Recipe;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;


class RecipeType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('preparationTime', IntegerType::class,[
                'label' => 'temps de préparation :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('cookingTime', IntegerType::class,[
                'label' => 'temps de cuisson :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('foodQuantity', IntegerType::class,[
                'label' => 'Quantité :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('foodQuantityType', ChoiceType::class, [
                'choices' => Recipe::getAvailableQuantityType(),
                'label' => 'Unité :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('difficulty', ChoiceType::class, [
                'choices' => Recipe::getAvailabledifficulties(),
                'label' => 'Difficulté :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('description', CKEditorType::class, [
                'config_name' => 'my_custom_config',
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('price', ChoiceType::class, [
                'choices' => Recipe::getAvailablePrices(),
                'label' => 'Prix :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('isFavorite', CheckboxType::class, [
                'required' => false,
                'label' => 'Favoris ?',
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ])
            ->add('isPublic', CheckboxType::class, [
                'required' => false,
                'label' => 'Public ?',
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false,
                'label' => 'Image :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('recipeIngredients', CollectionType::class, [
                'entry_type' => RecipeIngredientType::class,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Need context for the name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
