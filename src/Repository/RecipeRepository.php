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
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @param Category|null $category
     * @param Tag|null      $tag
     *
     * @return QueryBuilder
     */
    public function queryByFilters(?Category $category, ?Tag $tag): QueryBuilder
    {
        $qb = $this->createQueryBuilder('recipe')
            ->leftJoin('recipe.tags', 't')
            ->addSelect('t');

        if (null !== $category) {
            $qb->andWhere('recipe.category = :category')
                ->setParameter('category', $category);
        }

        if (null !== $tag) {
            $qb->andWhere(':tag MEMBER OF recipe.tags')
                ->setParameter('tag', $tag);
        }

        return $qb;
    }

    /**
     * @param User|null     $author
     * @param Category|null $category
     * @param Tag|null      $tag
     *
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
}
