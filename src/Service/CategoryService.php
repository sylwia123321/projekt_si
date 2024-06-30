<?php
/**
 * Category service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CategoryService.
 */
class CategoryService implements CategoryServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    private EntityManagerInterface $entityManager;
    private PaginatorInterface $paginator;
    private CategoryRepository $categoryRepository;
    private RecipeRepository $recipeRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     * @param CategoryRepository $categoryRepository
     * @param RecipeRepository $recipeRepository
     */
    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator, CategoryRepository $categoryRepository, RecipeRepository $recipeRepository)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->categoryRepository = $categoryRepository;
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * @param int $page
     * @return PaginationInterface
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        $query = $this->categoryRepository->queryAll();

        return $this->paginator->paginate(
            $query,
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * @param int $id
     * @return Category|null
     */
    public function getCategoryById(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }

    /**
     * @param Category $category
     * @return void
     */
    public function save(Category $category): void
    {
        $this->categoryRepository->save($category);
    }

    /**
     * @param Category $category
     * @return void
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }

    /**
     * Can Category be deleted?
     *
     * @param Category $category Category entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Category $category): bool
    {
        try {
            $result = $this->recipeRepository->countByCategory($category);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException $e) {
            return false;
        }
    }

    /**
     * Find by id.
     *
     * @param int $id Category id
     *
     * @return Category|null Category entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Category
    {
        return $this->categoryRepository->findOneById($id);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->categoryRepository->findAll();
    }
}
