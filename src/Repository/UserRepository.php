<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

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
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $email
     *
     * @return User|null
     *
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
     * user - user (int).
     *
     * @param array $params
     *
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

    /**
     * @param string $email
     *
     * @return mixed
     */
    public function findBySimilarEmail($email)
    {
        return $this->createQueryBuilder('u')
            ->where('u.email LIKE :email')
            ->setParameter('email', "$email%")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string $email
     *
     * @return mixed
     *
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function totalCountByEmail($email = null)
    {
        $qb = $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
        ;

        if (null !== $email) {
            $qb->where('u.email LIKE :email')
                ->setParameter('email', "%$email%")
            ;
        }

        $countArray = $qb->getQuery()->getSingleResult();
        $count = (int) $countArray[1] ?? 0;

        return $count;
    }

    /**
     * @param array $params
     * @param bool $asCount
     *
     * @return mixed
     *
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findAllWithOffsetAndLimitByEmail(array $params = [], $asCount = false)
    {
        $email = $params['email'] ?? null;
        $offset = $params['offset'] ?? null;
        $limit = $params['limit'] ?? null;
        $userHavePosts = $params['user_have_posts'] ?? null;
        $userHaveComments = $params['user_have_comments'] ?? null;

        $qb = $this->createQueryBuilder('u');

        if (null !== $email) {
            $qb->andWhere('u.email LIKE :email')
                ->setParameter('email', "$email%")
            ;
        }

        if ($userHaveComments) {
            $qb->leftJoin('u.comments', 'c')
                ->having('COUNT(c.id) > 0')
                ->groupBy('u.id')
            ;
        }

        if ($userHavePosts) {
            $qb->leftJoin('u.posts', 'p')
                ->having('COUNT(p.id) > 0')
                ->groupBy('u.id')
            ;
        }

        if (null !== $offset) {
            $qb->setFirstResult($offset);
        }

        if ($asCount) {
            $users = $qb->getQuery()->getResult();

            return count($users);
        }

        if (null !== $limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
}
