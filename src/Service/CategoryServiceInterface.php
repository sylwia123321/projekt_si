<?php
/**
 * Category service interface.
 */

namespace App\Service;

use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * interface CategoryServiceInterface.
 */
interface CategoryServiceInterface
{
    public function getPaginatedList(int $page): PaginationInterface;

    public function getCategoryById(int $id): ?Category;

    public function save(Category $category): void;

    public function delete(Category $category): void;

    public function canBeDeleted(Category $category): bool;

    public function findOneById(int $id): ?Category;

    public function findAll(): array;
}
