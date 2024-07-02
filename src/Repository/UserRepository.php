<?php
/**
 *  User repository.
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $entityManager;
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
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

    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('user');
    }

    public function delete(User $user): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($user);
        $entityManager->flush();
    }

    private function deleteRelatedEntities(User $user): void
    {
        $recipes = $user->getRecipes();

        foreach ($recipes as $recipe) {
            $this->entityManager->remove($recipe);
        }

        $this->entityManager->flush();
    }
}
