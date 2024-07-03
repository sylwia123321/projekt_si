<?php
/**
 *  Recipe repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * Retrieves a QueryBuilder instance to query Recipe entities filtered by category and/or tag.
     *
     * @param Category|null $category Optional category filter
     * @param Tag|null      $tag      Optional tag filter
     *
     * @return QueryBuilder The QueryBuilder instance
     */
    public function queryByFilters(?Category $category, ?Tag $tag): QueryBuilder
    {
        $qb = $this->createQueryBuilder('recipe')
            ->leftJoin('recipe.tags', 't')
            ->addSelect('t');

        if ($category instanceof Category) {
            $qb->andWhere('recipe.category = :category')
                ->setParameter('category', $category);
        }

        if ($tag instanceof Tag) {
            $qb->andWhere(':tag MEMBER OF recipe.tags')
                ->setParameter('tag', $tag);
        }

        return $qb;
    }

    /**
     * Retrieves a QueryBuilder instance to query Recipe entities filtered by author, category, and/or tag.
     *
     * @param User|null     $author   Optional author filter
     * @param Category|null $category Optional category filter
     * @param Tag|null      $tag      Optional tag filter
     *
     * @return QueryBuilder The QueryBuilder instance
     */
    public function queryByAuthorAndFilters(?User $author, ?Category $category, ?Tag $tag): QueryBuilder
    {
        $qb = $this->createQueryBuilder('recipe')
            ->leftJoin('recipe.tags', 't')
            ->addSelect('t')
            ->where('recipe.author = :author')
            ->setParameter('author', $author);

        if ($category instanceof Category) {
            $qb->andWhere('recipe.category = :category')
                ->setParameter('category', $category);
        }

        if ($tag instanceof Tag) {
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
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('recipe')
            ->leftJoin('recipe.category', 'category')
            ->leftJoin('recipe.tags', 'tags')
            ->addSelect('category', 'tags')
            ->orderBy('recipe.updatedAt', 'DESC');
    }

    /**
     * Retrieves an array of Recipe entities ordered by rating in descending order.
     *
     * @return Recipe[] An array of Recipe entities
     */
    public function findTopRated(): array
    {
        return $this->createQueryBuilder('recipe')
            ->orderBy('recipe.rating', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Adds a Recipe entity to the EntityManager.
     *
     * @param Recipe $recipe The Recipe entity to add
     * @param bool   $flush  Whether to flush changes immediately
     */
    public function add(Recipe $recipe, bool $flush = false): void
    {
        $this->_em->persist($recipe);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
