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
use App\Entity\Tag;

/**
 * Class RecipeService.
 */
class RecipeService implements RecipeServiceInterface
{
    /**
     * Items per page.
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService  Category service
     * @param PaginatorInterface       $paginator        Paginator
     * @param TagServiceInterface      $tagService       Tag service
     * @param RecipeRepository         $recipeRepository Recipe repository
     */
    public function __construct(
        private readonly CategoryServiceInterface $categoryService,
        private readonly PaginatorInterface $paginator,
        private readonly TagServiceInterface $tagService,
        private readonly RecipeRepository $recipeRepository
    ) {
    }

    /**
     * @param int       $page
     * @param User|null $author
     * @param int|null  $categoryId
     * @param int|null  $tagId
     *
     * @return PaginationInterface
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPaginatedList(int $page, ?User $author, ?int $categoryId, ?int $tagId): PaginationInterface
    {
        $category = null !== $categoryId ? $this->categoryService->findOneById($categoryId) : null;
        $tag = null !== $tagId ? $this->tagService->findOneById($tagId) : null;

        return $this->paginator->paginate(
            $this->recipeRepository->queryByAuthorAndFilters($author, $category, $tag),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }


    /**
     * @param Recipe $recipe
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Recipe $recipe): void
    {
        $this->recipeRepository->save($recipe);
    }

    /**
     * @param Recipe $recipe
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Recipe $recipe): void
    {
        $this->recipeRepository->delete($recipe);
    }

    /**
     * @param int      $page
     * @param int|null $categoryId
     * @param int|null $tagId
     *
     * @return PaginationInterface
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAllPaginatedList(int $page, ?int $categoryId, ?int $tagId): PaginationInterface
    {
        $category = null !== $categoryId ? $this->categoryService->findOneById($categoryId) : null;
        $tag = null !== $tagId ? $this->tagService->findOneById($tagId) : null;

        return $this->paginator->paginate(
            $this->recipeRepository->queryByFilters($category, $tag),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find by title.
     *
     * @param string $title Tag title
     *
     * @return Tag|null Tag entity
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneByTitle($title);
    }
}
