<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\EntityManager;

/**
 * Class RecipeRepository.
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @param Category|null $category
     * @param Tag|null $tag
     * @return QueryBuilder
     */
    public function queryByFilters(?Category $category, ?Tag $tag): QueryBuilder
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.tags', 't')
            ->addSelect('t');

        if (null !== $category) {
            $qb->andWhere('r.category = :category')
                ->setParameter('category', $category);
        }

        if (null !== $tag) {
            $qb->andWhere(':tag MEMBER OF r.tags')
                ->setParameter('tag', $tag);
        }

        return $qb;
    }


    /**
     * Find top-rated recipes using native SQL query.
     *
     * @return Recipe[]
     */
    public function findTopRatedRecipes(): array
    {
        $entityManager = $this->getEntityManager();

        $connection = $entityManager->getConnection();
        $sql = '
            SELECT r.*, AVG(ra.score) as avgScore
            FROM recipes r
            LEFT JOIN ratings ra ON r.id = ra.recipe_id
            GROUP BY r.id
            ORDER BY avgScore DESC
            LIMIT 10
        ';

        $statement = $connection->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAllAssociative();

        $recipes = [];
        foreach ($results as $result) {
            $recipe = $this->find($result['id']);
            if ($recipe !== null) {
                $recipes[] = [
                    'recipe' => $recipe,
                    'avgScore' => $result['avgScore'],
                ];
            }
        }

        return $recipes;
    }

    /**
     * @param User|null $author
     * @param Category|null $category
     * @param Tag|null $tag
     * @return QueryBuilder
     */
    public function queryByAuthorAndFilters(?User $author, ?Category $category, ?Tag $tag): QueryBuilder
    {
        $qb = $this->createQueryBuilder('recipe')
            ->leftJoin('recipe.tags', 't')
            ->addSelect('t')
            ->where('recipe.author = :author')
            ->setParameter('author', $author);

        if ($category) {
            $qb->andWhere('recipe.category = :category')
                ->setParameter('category', $category);
        }

        if ($tag) {
            $qb->andWhere(':tag MEMBER OF recipe.tags')
                ->setParameter('tag', $tag);
        }

        return $qb;
    }


    /**
     * Delete entity.
     *
     * @param Recipe $recipe Recipe entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Recipe $recipe): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->remove($recipe);
        $this->_em->flush();
    }

    /**
     * Save entity.
     *
     * @param Recipe $recipe Recipe entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Recipe $recipe): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->persist($recipe);
        $this->_em->flush();
    }
    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('recipe')
            ->leftJoin('recipe.category', 'category')
            ->leftJoin('recipe.tags', 'tags')
            ->addSelect('category', 'tags')
            ->orderBy('recipe.updatedAt', 'DESC');
    }

}
