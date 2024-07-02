<?php
/**
 * Tag service.
 */

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TagService.
 */
class TagService implements TagServiceInterface
{
    private PaginatorInterface $paginator;
    private TagRepository $tagRepository;

    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    public function __construct(PaginatorInterface $paginator, TagRepository $tagRepository)
    {
        $this->paginator = $paginator;
        $this->tagRepository = $tagRepository;
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        $query = $this->tagRepository->queryAll();

        return $this->paginator->paginate(
            $query,
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Tag $tag): void
    {
        $this->tagRepository->save($tag);
    }

    /**
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Tag $tag): void
    {
        $this->tagRepository->delete($tag);
    }

    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneByTitle($title);
    }

    public function findOneById(int $id): ?Tag
    {
        return $this->tagRepository->findOneById($id);
    }

    public function findAll(): array
    {
        return $this->tagRepository->findAll();
    }

    public function findByTitles(array $titles): array
    {
        $tags = $this->tagRepository->createQueryBuilder('t')
            ->where('LOWER(t.title) IN (:titles)')
            ->setParameter('titles', array_map('strtolower', $titles))
            ->getQuery()
            ->getResult();

        $tagMap = [];
        foreach ($tags as $tag) {
            $tagMap[strtolower($tag->getTitle())] = $tag;
        }

        return $tagMap;
    }
}
