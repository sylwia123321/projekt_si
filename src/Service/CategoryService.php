<?php
namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class CategoryService implements CategoryServiceInterface
{
private const PAGINATOR_ITEMS_PER_PAGE = 10;

public function __construct(private readonly CategoryRepository $categoryRepository, private readonly PaginatorInterface $paginator)
{
}

public function getPaginatedList(int $page): PaginationInterface
{
return $this->paginator->paginate(
$this->categoryRepository->queryAll(),
$page,
self::PAGINATOR_ITEMS_PER_PAGE
);
}

public function getCategoryById(int $id): ?Category
{
return $this->categoryRepository->find($id);
}
    /**
     * Save entity.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void
    {
        if (null == $category->getId()) {
            $category->setCreatedAt(new \DateTimeImmutable());
        }
        $category->setUpdatedAt(new \DateTimeImmutable());

        $this->categoryRepository->save($category);
    }
    /**
     * Delete entity.
     *
     * @param Category $category Category entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }
}