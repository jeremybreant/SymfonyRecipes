<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('time', IntegerType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'temps',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('peopleRequired', IntegerType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nbr de personne nécessaire',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('difficulty',  IntegerType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'difficulté',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Prix',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('isFavorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Favoris ?',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'multiple' => true,
                'choice_label' => 'name',
                'query_builder' => function (IngredientRepository $ir) {
                    return $ir->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC');
                },
                'expanded' => true,
                'label' => 'les ingrédients'
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
