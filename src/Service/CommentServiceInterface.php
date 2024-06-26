<?php
namespace App\Service;

use App\Entity\Comment;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\ORM\EntityManagerInterface;

interface CommentServiceInterface
{
    public function getPaginatedList(int $page): PaginationInterface;
    public function getCommentById(int $id): ?Comment;
    /**
     * Save entity.
     *
     * @param Comment $comment Comment entity
     */
    public function save(Comment $comment): void;
    /**
     * Delete entity.
     *
     * @param Comment $comment Comment entity
     */
    public function delete(Comment $comment): void;
    /**
     * Can Comment be deleted?
     *
     * @param Comment $comment Comment entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Comment $comment): bool;

    /**
     * Find by id.
     *
     * @param int $id Comment id
     *
     * @return Comment|null Comment entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Comment;

    public function findAll(): array;
}

