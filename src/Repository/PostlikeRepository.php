<?php

namespace App\Repository;

use App\Entity\Postlike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Postlike|null find($id, $lockMode = null, $lockVersion = null)
 * @method Postlike|null findOneBy(array $criteria, array $orderBy = null)
 * @method Postlike[]    findAll()
 * @method Postlike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostlikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postlike::class);
    }

    // /**
    //  * @return Postlike[] Returns an array of Postlike objects
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
    public function findOneBySomeField($value): ?Postlike
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
