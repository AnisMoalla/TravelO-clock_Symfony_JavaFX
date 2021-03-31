<?php

namespace App\Repository;

use App\Entity\PostForum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostForum|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostForum|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostForum[]    findAll()
 * @method PostForum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostForumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostForum::class);
    }

    // /**
    //  * @return PostForum[] Returns an array of PostForum objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostForum
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
