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
use Doctrine\Persistence\ManagerRegistry;

/**
 * RecipeRepository
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * Query recipes by author and filters.
     *
     * @param User      $author   Author entity
     * @param Category|null $category Category entity
     * @param Tag|null      $tag      Tag entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByAuthorAndFilters(User $author, ?Category $category, ?Tag $tag)
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.author = :author')
            ->setParameter('author', $author);

        if ($category) {
            $qb->andWhere('r.category = :category')
                ->setParameter('category', $category);
        }

        if ($tag) {
            $qb->andWhere(':tag MEMBER OF r.tags')
                ->setParameter('tag', $tag);
        }

        return $qb->getQuery();
    }

    /**
     * Save entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function save(Recipe $recipe): void
    {
        $this->_em->persist($recipe);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function delete(Recipe $recipe): void
    {
        $this->_em->remove($recipe);
        $this->_em->flush();
    }
}
