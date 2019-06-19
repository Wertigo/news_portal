<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    /**
     * TagRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function findBySimilarName($name)
    {
        return $this->createQueryBuilder('t')
            ->where('t.name LIKE :name')
            ->setParameter('name', "%$name%")
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
