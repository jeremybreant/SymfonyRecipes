<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * This method allow us to find a specified number of public recipes
     *
     * @param int $nbRecipes
     * @return array
     */
    public function findPublicRecipe(?int $nbRecipes): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.isPublic = 1')
            ->orderBy('r.createdAt', 'DESC');
        if ($nbRecipes !== 0 || $nbRecipes !== null) {
            $queryBuilder->setMaxResults($nbRecipes);
        }
        return $queryBuilder
            ->getQuery()
            ->getResult();

    }

    public function findPublicRecipeswithCategoryQuery(?int $nbRecipes, $categoryId): Query
    {
        return $this->createQueryBuilder('r')
            ->where('r.isPublic = 1')
            ->andWhere(':categoryValue MEMBER OF r.categories')
            ->setParameter('categoryValue', $categoryId)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery();
    }

    public function findRecipesBasedOnNameQuery(?int $nbRecipes, $keyword): Query
    {
        return $this->createQueryBuilder('r')
            ->where('r.isPublic = 1')
            ->andWhere('r.name LIKE :keyword')
            ->setParameter('keyword', '%'.$keyword.'%')
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery();
            
    }

    public function save(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Recipe[] Returns an array of Recipe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recipe
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
