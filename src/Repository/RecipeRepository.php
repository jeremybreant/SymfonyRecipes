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
    public function findPublicRecipeQuery(?int $nbRecipes): Query
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.isPublic = 1')
            ->andWhere('r.status = :status')
            ->setParameter('status', "Approuvée")
            ->orderBy('r.createdAt', 'DESC');
        if ($nbRecipes !== 0 && $nbRecipes !== null) {
            $queryBuilder->setMaxResults($nbRecipes);
        }
        return $queryBuilder
            ->getQuery();

    }

    public function findPublicRecipeswithCategoryQuery(?int $nbRecipes, $categoryId): Query
    {
        return $this->createQueryBuilder('r')
            ->where('r.isPublic = 1')
            ->andWhere(':categoryValue MEMBER OF r.categories')
            ->setParameter('categoryValue', $categoryId)
            ->andWhere('r.status = :status')
            ->setParameter('status', "Approuvée")
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery();
    }

    public function findRecipesBasedOnNameQuery(?int $nbRecipes, $keyword): Query
    {
        return $this->createQueryBuilder('r')
            ->where('r.isPublic = 1')
            ->andWhere('r.status = :status')
            ->setParameter('status', "Approuvée")
            ->andWhere('r.name LIKE :keyword')
            ->setParameter('keyword', '%'.$keyword.'%')
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery();
            
    }

    public function findRecipesLikedByTheUserQuery(?int $nbRecipes, $user): Query
    {
        return $this->createQueryBuilder('r')
            ->where('r.isPublic = 1')
            ->andWhere('r.status = :status')
            ->setParameter('status', "Approuvée")
            ->innerJoin('r.usersLikingThisRecipe', 'u') // Utilisation de innerJoin pour correspondre à la table de liaison
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery();   
    }

    public function findUserRecipesrQuery(?int $nbRecipes, $user): Query
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.user', 'u') // Utilisation de innerJoin pour correspondre à la table de liaison
            ->where('u.id = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('r.name', 'ASC')
            ->getQuery();      
    }

    public function findRandomPublicRecipe():?Recipe
    {
        $results = $this->createQueryBuilder('r')
            ->select('r.id')
            ->where('r.isPublic = 1')
            ->andWhere('r.status = :status')
            ->setParameter('status', "Approuvée")
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

        $idArray = array_column($results, 'id');
        $randInt = mt_rand(0, count($idArray)-1);
        $randomId = $idArray[$randInt];

        return $this->createQueryBuilder('r')
            ->where('r.id = :randomId')
            ->setParameter('randomId', $randomId)
            ->getQuery()
            ->getOneOrNullResult();
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
