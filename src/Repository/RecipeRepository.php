<?php
/**
 * Recipe repository.
 */

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
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Recipe>
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
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial recipe.{id, createdAt, updatedAt, title, description, ingredients, instructions}', // Added fields
                'partial category.{id, title}'
            )
            ->join('recipe.category', 'category')
            ->orderBy('recipe.updatedAt', 'DESC');
    }

    /**
     * Count recipes by category.
     *
     * @param Category $category Category
     *
     * @return int Number of recipes in category
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByCategory(Category $category): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('recipe.id'))
            ->where('recipe.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Query recipes by author and filters.
     *
     * @param User $author Author entity
     * @param Category|null $category Category entity
     * @param Tag|null $tag Tag entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByAuthorAndFilters(User $author, ?Category $category, ?Tag $tag): QueryBuilder
    {
        $qb = $this->createQueryBuilder('recipe')
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
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('recipe');
    }
}
