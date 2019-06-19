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

    /**
     * Available indexes for $params array:
     * author - user id (int)
     * status - post status (int)
     * createdFrom = datetime: yyyy-mm-dd (string)
     * createdTo = datetime: yyyy-mm-dd (string)
     *
     *
     * @param array $params
     * @param bool $asQuery
     * @return \Doctrine\ORM\Query|mixed
     */
    public function findAllExceptDrafts(array $params = [], $asQuery = false)
    {
        $builder = $this->createQueryBuilder('p')
            ->where('p.status <> :status')
            ->setParameter('status', Post::STATUS_DRAFT)
            ->orderBy('p.created_at', 'ASC')
        ;

        if (isset($params['author']) && $params['author']) {
            $builder->andWhere('p.author = :author')
                ->setParameter('author', $params['author']);
        }

        if (isset($params['status']) && $params['status']) {
            $builder->andWhere('p.status = :statusParam')
                ->setParameter('statusParam', $params['status']);
        }

        if (isset($params['createdFrom']) && $params['createdFrom']) {
            $builder->andWhere('p.created_at >= :createdFrom')
                ->setParameter('createdFrom', $params['createdFrom']);
        }

        if (isset($params['createdTo']) && $params['createdTo']) {
            $builder->andWhere('p.created_at <= :createdTo')
                ->setParameter('createdTo', $params['createdTo']);
        }

        $query = $builder->getQuery();

        if ($asQuery) {
            return $query;
        }

        return $query->getResult();
    }
}