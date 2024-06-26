<?php

namespace App\Service;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommentService implements CommentServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 10;
    private EntityManagerInterface $entityManager;
    private PaginatorInterface $paginator;
    private $commentRepository;

    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->commentRepository = $entityManager->getRepository(Comment::class);
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        $query = $this->commentRepository->queryAll();
        return $this->paginator->paginate(
            $query,
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function getCommentById(int $id): ?Comment
    {
        return $this->commentRepository->find($id);
    }

    /**
     * Save entity.
     *
     * @param Comment $comment Comment entity
     */
    public function save(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment entity
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }

    /**
     * Can Comment be deleted?
     *
     * @param Comment $comment Comment entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Comment $comment): bool
    {
        try {
            $result = $this->recipeRepository->countByComment($comment);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }

    /**
     * Find by id.
     *
     * @param int $id Comment id
     *
     * @return Comment|null Comment entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Comment
    {
        return $this->commentRepository->findOneById($id);
    }

    public function findAll(): array
    {
        return $this->commentRepository->findAll();
    }
}

