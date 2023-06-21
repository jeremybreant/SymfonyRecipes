<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\RecipeIngredient;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RecipeIngredientType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'label' => 'Ingrédient :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'query_builder' => function (IngredientRepository $ir) {
                    return $ir->createQueryBuilder('i')
                        ->where('i.user = :user')
                        ->orderBy('i.name', 'ASC')
                        ->setParameter('user' , $this->token->getToken()->getUser());
                }
            ])
            ->add('quantity', NumberType::class,[
                'label' => 'quantité :',
                'required' => false,
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('unitType', ChoiceType::class, [
                'choices' => RecipeIngredient::getAvailableUnits(),
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
