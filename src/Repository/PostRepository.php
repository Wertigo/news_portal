<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    /**
     * PostRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return mixed
     */
    public function findPostsForIndexPage()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->setParameter('status', Post::STATUS_PUBLISHED)
            ->orderBy('p.created_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function findPublishedPostsByAuthor(User $author)
    {
        return $this->createQueryBuilder('p')
            ->where('p.status = :status')
            ->setParameter('status', Post::STATUS_PUBLISHED)
            ->andWhere('p.author = :author')
            ->setParameter('author', $author)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return mixed
     */
    public function findPublishedPosts()
    {
        return $this->createQueryBuilder('p')
            ->where('p.status = :status')
            ->setParameter('status', Post::STATUS_PUBLISHED)
            ->getQuery()
            ->getResult()
        ;
    }
}