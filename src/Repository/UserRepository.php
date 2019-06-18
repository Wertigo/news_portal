<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $email
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findActivatedUserByEmail($email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->andWhere('u.status = :status')
            ->setParameter('status', User::STATUS_ACTIVE)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return mixed
     */
    public function findAllActivatedUsers()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.status = :status')
            ->setParameter('status', User::STATUS_ACTIVE)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Available indexes for $params array:
     * user - user (int)
     *
     * @param array $params
     * @return \Doctrine\ORM\Query
     */
    public function findAllQuery(array $params = [])
    {
        $qb = $this->createQueryBuilder('u');

        if (isset($params['user'])) {
            $qb->where('u.id = :user')
                ->setParameter('user', $params['user'])
            ;
        }

        return $qb->getQuery();
    }
}
