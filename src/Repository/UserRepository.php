<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    /**
     * Constructor.
     *
     * @param ManagerRegistry        $registry      Manager registry
     * @param EntityManagerInterface $entityManager Entity manager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $entityManager;
    }

    /**
     * Save entity.
     *
     * @param User $user User entity
     */
    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param User $user
     * @return void
     * @throws \Exception
     */
    public function deleteUserWithRelatedEntities(User $user): void
    {
        $this->entityManager->beginTransaction();

        try {
            $this->deleteRelatedEntities($user);
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('user');
    }

    /**
     * @param User $user
     * @return void
     */
    public function delete(User $user): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($user);
        $entityManager->flush();
    }

    /**
     * Delete related entities (e.g., recipes) associated with the user.
     *
     * @param User $user User entity
     */
    private function deleteRelatedEntities(User $user): void
    {
        $recipes = $user->getRecipes();

        foreach ($recipes as $recipe) {
            $this->entityManager->remove($recipe);
        }

        $this->entityManager->flush();
    }
}
