<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ApprobRecipeCrudController extends AbstractCrudController
{

    private $adminUrlGenerator;
    private $router;

    public function __construct(AdminUrlGenerator $adminUrlGenerator, RouterInterface $router)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->router = $router;
    }

    public static function getEntityFqcn(): string
    {
        return Recipe::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Demandes d\'approbation')
            ->setEntityLabelInSingular('Demande d\'approbation')

            ->setPageTitle("index", "LaPoêlée - Administration des demandes d'approbation")
            ->showEntityActionsInlined()

            ->setPaginatorPageSize(10)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel(),
            IdField::new('id')
                ->setDisabled()
                ->setColumns(6),
            ChoiceField::new('status')->setChoices([
                Recipe::STATUS_NOT_APPROVED => Recipe::STATUS_NOT_APPROVED,
                Recipe::STATUS_IN_APPROBATION => Recipe::STATUS_IN_APPROBATION,
                Recipe::STATUS_APPROVED => Recipe::STATUS_APPROVED,
                Recipe::STATUS_REFUSED => Recipe::STATUS_REFUSED,
            ])
                //change the way tag is displayed
                ->setTemplatePath('admin/field/tag_field_template.html.twig')
                ->setColumns(6),

            DateTimeField::new("updatedAt")
                ->setDisabled(),
            AssociationField::new('categories')
                ->setColumns(6),

            FormField::addPanel("Informations à vérifier"),
            TextField::new('name')
                ->setDisabled()
                ->setColumns(6),
            NumberField::new('preparationTime')
                ->setDisabled()
                ->hideOnIndex()
                ->setColumns(6),
            NumberField::new('cookingTime')
                ->setDisabled()
                ->hideOnIndex()
                ->setColumns(6),
            NumberField::new('foodQuantity')
                ->setDisabled()
                ->hideOnIndex()
                ->setColumns(6),
            TextField::new('foodQuantityType')
                ->setDisabled()
                ->hideOnIndex()
                ->setColumns(6),
            TextField::new('difficulty')
                ->setDisabled()
                ->hideOnIndex()
                ->setColumns(6),
            TextareaField::new('description')
                ->setDisabled()
                ->renderAsHtml()
                ->setColumns(12),

            FormField::addPanel("Image info"),

            CollectionField::new('images', 'Image')
                ->hideOnIndex()
                ->hideOnForm()
                ->setTemplatePath('admin/field/image_collection_field_template.html.twig')

        ];
    }

    // Surchargez la méthode createIndexQueryBuilder pour appliquer directement le filtre
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        // Appliquez la condition pour filtrer les recettes ayant le tag "récent"
        $queryBuilder->andWhere('entity.status LIKE :status_in_approbation')
            ->setParameter('status_in_approbation', Recipe::STATUS_IN_APPROBATION);

        return $queryBuilder;
    }


    public function configureActions(Actions $actions): Actions
    {
        $refuseRecipe = Action::new('refuseApprobation', 'Refuser l\'approbation', 'fas fa-xmark')
            ->linkToCrudAction('refuseApprobation')
            ->setCssClass('btn btn-danger');

        $acceptRecipe = Action::new('approbate', 'Approuver', 'fas fa-check')
            ->linkToCrudAction('approbate')
            ->setCssClass('btn btn-success');

        $seeFromClientView = Action::new('seeFromCustomerView', 'Voir via client', 'fas fa-eye')
            ->linkToCrudAction('seeFromCustomerView')
            ->setCssClass('btn btn-info');


        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_DETAIL, $refuseRecipe)
            ->add(Crud::PAGE_DETAIL, $acceptRecipe)
            ->add(Crud::PAGE_DETAIL, $seeFromClientView)
            ->remove(Crud::PAGE_INDEX, Action::NEW )
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
        ;
    }

    public function refuseApprobation(AdminContext $context, EntityManagerInterface $entityManager)
    {
        /** @var Recipe $recipe */
        $recipe = $context->getEntity()->getInstance();
        $recipe->setStatus(Recipe::STATUS_REFUSED);
        $entityManager->persist($recipe);
        $entityManager->flush();
        $this->addFlash('success', 'L\'approbation a été refusée avec succès !');

        $url = $this->adminUrlGenerator->setController(RecipeCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function approbate(AdminContext $context, EntityManagerInterface $entityManager)
    {
        /** @var Recipe $recipe */
        $recipe = $context->getEntity()->getInstance();
        $recipe->setStatus(Recipe::STATUS_APPROVED);
        $entityManager->persist($recipe);
        $entityManager->flush();
        $this->addFlash('success', 'L\'approbation a été accepté avec succès !');

        $url = $this->adminUrlGenerator->setController(RecipeCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function seeFromCustomerView(AdminContext $context, EntityManagerInterface $entityManager, Request $request)
    {
        /** @var Recipe $recipe */
        $recipe = $context->getEntity()->getInstance();

        return new RedirectResponse($this->router->generate('recipe.show', ['id' => $recipe->getId()]));
    }

}
