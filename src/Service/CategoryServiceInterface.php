<?php
namespace App\Service;

use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface CategoryServiceInterface
{
public function getPaginatedList(int $page): PaginationInterface;
public function getCategoryById(int $id): ?Category;
    /**
     * Save entity.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void;
    /**
     * Delete entity.
     *
     * @param Category $category Category entity
     */
    public function delete(Category $category): void;
}