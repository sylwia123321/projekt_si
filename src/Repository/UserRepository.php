<?php
/**
 *  User repository.
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @throws \Exception
     */
    public function deleteUserWithRelatedEntities(User $user): void
    {
        assert($this->_em instanceof EntityManager);
        $recipes = $this->_em
            ->getRepository(\App\Entity\Recipe::class)
            ->findBy(['author' => $user]);

        $this->_em->beginTransaction();

        try {
            foreach ($recipes as $recipe) {
                $this->_em->remove($recipe);
            }

            $this->_em->remove($user);

            $this->_em->flush();
            $this->_em->commit();
        } catch (\Exception $e) {
            $this->_em->rollback();
            throw $e;
        }
    }

    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('user');
    }

    public function delete(User $user): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->remove($user);
        $this->_em->flush();
    }

    private function deleteRelatedEntities(User $user): void
    {
        assert($this->_em instanceof EntityManager);
        $recipes = $this->recipes->getAuthor();

        foreach ($recipes as $recipe) {
            $this->_em->remove($recipe);
        }
        $this->_em->flush();
    }
}
