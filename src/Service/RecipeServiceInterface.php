<?php
/**
 * Recipe service interface.
 */

namespace App\Service;

use App\Entity\Recipe;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface RecipeServiceInterface.
 */
interface RecipeServiceInterface
{
    /**
     * Get paginated list of recipes.
     *
     * @param int       $page       Page number
     * @param User|null $author     Optional author filter
     * @param int|null  $categoryId Optional category ID filter
     * @param int|null  $tagId      Optional tag ID filter
     *
     * @return PaginationInterface<string, mixed> Paginated list of recipes
     */
    public function getPaginatedList(int $page, ?User $author, ?int $categoryId, ?int $tagId): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function save(Recipe $recipe): void;

    /**
     * Delete entity.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function delete(Recipe $recipe): void;

    /**
     * Get all recipes paginated list.
     *
     * @param int      $page       Page number
     * @param int|null $categoryId Optional category ID filter
     * @param int|null $tagId      Optional tag ID filter
     *
     * @return PaginationInterface<string, mixed> Paginated list of recipes
     */
    public function getAllPaginatedList(int $page, ?int $categoryId, ?int $tagId): PaginationInterface;
}
