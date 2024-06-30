<?php
/**
 *  Tag repository.
 */

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * @return QueryBuilder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('tag.updatedAt', 'DESC');
    }

    /**
     * @param Tag $tag
     *
     * @return void
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Tag $tag): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->persist($tag);
        $this->_em->flush();
    }

    /**
     * @param Tag $tag
     *
     * @return void
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Tag $tag): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->remove($tag);
        $this->_em->flush();
    }

    /**
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    private function getOrCreateQueryBuilder(?QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('tag');
    }
}
