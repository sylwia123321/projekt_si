<?php
/**
 * Recipe service interface.
 */

namespace App\Service;

use App\Entity\Recipe;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;
use App\Entity\Tag;

/**
 * Interface RecipeServiceInterface.
 */
interface RecipeServiceInterface
{
    /**
     * @param int       $page
     * @param User|null $author
     * @param int|null  $categoryId
     * @param int|null  $tagId
     *
     * @return PaginationInterface
     */
    public function getPaginatedList(int $page, ?User $author, ?int $categoryId, ?int $tagId): PaginationInterface;

    /**
     * @param Recipe $recipe
     *
     * @return void
     */
    public function save(Recipe $recipe): void;

    /**
     * @param Recipe $recipe
     *
     * @return void
     */
    public function delete(Recipe $recipe): void;

    /**
     * @param int      $page
     * @param int|null $categoryId
     * @param int|null $tagId
     *
     * @return PaginationInterface
     */
    public function getAllPaginatedList(int $page, ?int $categoryId, ?int $tagId): PaginationInterface;

    /**
     * @param string $title
     *
     * @return Tag|null
     */
    public function findOneByTitle(string $title): ?Tag;
}
