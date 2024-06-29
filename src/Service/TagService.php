<?php
/**
 * Tag service.
 */

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\NonUniqueResultException;

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

    /**
     * Constructor.
     *
     * @param TagRepository      $tagRepository Tag repository
     * @param PaginatorInterface $paginator     Paginator
     */
    public function __construct(PaginatorInterface $paginator, TagRepository $tagRepository)
    {
        $this->paginator = $paginator;
        $this->tagRepository = $tagRepository;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
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
     * Save entity.
     *
     * @param Tag $tag Tag entity
     */
    public function save(Tag $tag): void
    {
        $this->tagRepository->save($tag);
    }

    /**
     * Delete entity.
     *
     * @param Tag $tag Tag entity
     */
    public function delete(Tag $tag): void
    {
        $this->tagRepository->delete($tag);
    }

    /**
     * Find by title.
     *
     * @param string $title Tag title
     *
     * @return Tag|null Tag entity
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneByTitle($title);
    }

    /**
     * Find by id.
     *
     * @param int $id Tag id
     *
     * @return Tag|null Tag entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Tag
    {
        return $this->tagRepository->findOneById($id);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->tagRepository->findAll();
    }

    /**
     * @param array $titles
     * @return array
     */
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
