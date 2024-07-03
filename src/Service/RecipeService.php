<?php
/**
 * Recipe service.
 */

namespace App\Service;

use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;

/**
 * Class RecipeService.
 */
class RecipeService implements RecipeServiceInterface
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param CategoryRepository $categoryRepository Repository for categories
     * @param PaginatorInterface $paginator          Paginator service for pagination
     * @param TagRepository      $tagRepository      Repository for tags
     * @param RecipeRepository   $recipeRepository   Repository for recipes
     */
    public function __construct(private readonly CategoryRepository $categoryRepository, private readonly PaginatorInterface $paginator, private readonly TagRepository $tagRepository, private readonly RecipeRepository $recipeRepository)
    {
    }

    /**
     * Get paginated list of recipes.
     *
     * @param int       $page       Page number
     * @param User|null $author     Optional author filter
     * @param int|null  $categoryId Optional category ID filter
     * @param int|null  $tagId      Optional tag ID filter
     *
     * @return PaginationInterface Pagination object
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPaginatedList(int $page, ?User $author, ?int $categoryId, ?int $tagId): PaginationInterface
    {
        $category = null !== $categoryId ? $this->categoryRepository->findOneById($categoryId) : null;
        $tag = null !== $tagId ? $this->tagRepository->findOneById($tagId) : null;

        return $this->paginator->paginate(
            $this->recipeRepository->queryByAuthorAndFilters($author, $category, $tag),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function save(Recipe $recipe): void
    {
        $this->recipeRepository->save($recipe);
    }

    /**
     * Delete entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function delete(Recipe $recipe): void
    {
        $this->recipeRepository->delete($recipe);
    }

    /**
     * Get all recipes paginated list.
     *
     * @param int      $page       Page number
     * @param int|null $categoryId Optional category ID filter
     * @param int|null $tagId      Optional tag ID filter
     *
     * @return PaginationInterface Pagination object
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAllPaginatedList(int $page, ?int $categoryId, ?int $tagId): PaginationInterface
    {
        $category = null !== $categoryId ? $this->categoryRepository->findOneById($categoryId) : null;
        $tag = null !== $tagId ? $this->tagRepository->findOneById($tagId) : null;

        return $this->paginator->paginate(
            $this->recipeRepository->queryByFilters($category, $tag),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}
