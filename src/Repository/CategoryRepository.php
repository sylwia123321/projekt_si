<?php
/**
 * Category repository.
 */

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\EntityManager;

/**
 * Class CategoryRepository.
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    /**
     * Entity Manager.
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param ManagerRegistry        $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Category::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @return QueryBuilder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('category')
            ->orderBy('category.updatedAt', 'DESC');
    }

    /**
     * @param Category $category
     *
     * @return void
     */
    public function save(Category $category): void
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }

    /**
     * @param Category $category
     *
     * @return void
     *
     * @throws OptimisticLockException
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public function delete(Category $category): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->remove($category);
        $this->_em->flush();
    }
}
