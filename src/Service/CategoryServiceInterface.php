<?php

namespace App\Service;

use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * interface CategoryServiceInterface.
 */
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

    /**
     * Can Category be deleted?
     *
     * @param Category $category Category entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Category $category): bool;

    /**
     * Find by id.
     *
     * @param int $id Category id
     *
     * @return Category|null Category entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Category;

    public function findAll(): array;
}
