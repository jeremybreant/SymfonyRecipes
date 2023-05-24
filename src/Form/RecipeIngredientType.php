<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\RecipeIngredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Ingrédient :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('quantity', NumberType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'quantité :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('unitType', ChoiceType::class, [
                'choices' => RecipeIngredient::getAvailableUnits(),
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Unité :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredient::class,
        ]);
    }
}
