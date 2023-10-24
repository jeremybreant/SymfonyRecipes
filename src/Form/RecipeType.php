<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
                'attr' => [
                    'placeholder' => 'Nom'
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('preparationTime', IntegerType::class,[
                'label' => 'temps de préparation :',
                'attr' => [
                    'placeholder' => 'Minutes'
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('cookingTime', IntegerType::class,[
                'label' => 'temps de cuisson :',
                'attr' => [
                    'placeholder' => 'Minutes'
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('foodQuantity', IntegerType::class,[
                'label' => 'Quantité :',
                'attr' => [
                    'placeholder' => '10'
                ],
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
            ->add("description", TinymceType::class, [
                "attr" => [
                    "class" => "form-control",
                    "toolbar" => "bold italic underline | bullist numlist",
                    "menubar" => "",
                    "statusbar" => "",
                    'placeholder' => 'Étape 1: ...'
                ],
                'label' => 'Description :',
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
            ->add('isPublic', CheckboxType::class, [
                'required' => false,
                'label' => 'La recette est elle publique ?',
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'multiple' => true,
                'label' => 'Catégories :',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('images', FileType::class, [
                'label' => 'Ajouter une image :',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'multiple' => false,
                'mapped' => false,
                'required' => false
            ])
            ->add('recipeIngredients', CollectionType::class, [
                'entry_type' => RecipeIngredientType::class,
                'entry_options' => ['label' => false],
                'label' => false,
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
