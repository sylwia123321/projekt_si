<?php
/**
 *  Tag repository.
 */

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('tag.updatedAt', 'DESC');
    }

    /**
     * Save entity.
     *
     * @param Tag $tag Tag entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Tag $tag): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->persist($tag);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Tag $tag Tag entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Tag $tag): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->remove($tag);
        $this->_em->flush();
    }

    /**
     * Retrieves all Tag entities from the database.
     *
     * @return array An array of Tag entities
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * Finds Tag entities by their titles.
     *
     * @param array $titles An array of tag titles to search for
     *
     * @return array An array of Tag entities indexed by title in lowercase
     */
    public function findByTitles(array $titles): array
    {
        $tags = $this->createQueryBuilder('t')
            ->where('LOWER(t.title) IN (:titles)')
            ->setParameter('titles', array_map(strtolower(...), $titles))
            ->getQuery()
            ->getResult();

        $tagMap = [];
        foreach ($tags as $tag) {
            $tagMap[strtolower((string) $tag->getTitle())] = $tag;
        }

        return $tagMap;
    }

    /**
     * Retrieves a Tag entity by its ID.
     *
     * @param int $id The ID of the Tag entity to retrieve
     *
     * @return Tag|null The Tag entity, or null if not found
     */
    public function findOneById(int $id): ?Tag
    {
        return $this->find($id);
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(?QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('tag');
    }
}
