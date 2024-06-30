<?php
/**
 * Category service interface.
 */

namespace App\Service;

use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * interface CategoryServiceInterface.
 */
interface CategoryServiceInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * @param int $id
     *
     * @return Category|null
     */
    public function getCategoryById(int $id): ?Category;

    /**
     * @param Category $category
     *
     * @return void
     */
    public function save(Category $category): void;

    /**
     * @param Category $category
     *
     * @return void
     */
    public function delete(Category $category): void;

    /**
     * @param Category $category
     *
     * @return bool
     */
    public function canBeDeleted(Category $category): bool;

    /**
     * @param int $id
     *
     * @return Category|null
     */
    public function findOneById(int $id): ?Category;

    /**
     * @return array
     */
    public function findAll(): array;
}
