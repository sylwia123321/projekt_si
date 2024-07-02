<?php
/**
 * Recipe service interface.
 */

namespace App\Service;

use App\Entity\Recipe;
use App\Entity\Tag;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface RecipeServiceInterface.
 */
interface RecipeServiceInterface
{
    public function getPaginatedList(int $page, ?User $author, ?int $categoryId, ?int $tagId): PaginationInterface;

    public function save(Recipe $recipe): void;

    public function delete(Recipe $recipe): void;

    public function getAllPaginatedList(int $page, ?int $categoryId, ?int $tagId): PaginationInterface;

    public function findOneByTitle(string $title): ?Tag;
}
