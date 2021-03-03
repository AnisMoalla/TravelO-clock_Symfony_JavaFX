<?php

namespace App\Repository;

use App\Entity\PostFrum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostFrum|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostFrum|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostFrum[]    findAll()
 * @method PostFrum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostFrumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostFrum::class);
    }

    // /**
    //  * @return PostFrum[] Returns an array of PostFrum objects
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
    public function findOneBySomeField($value): ?PostFrum
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
