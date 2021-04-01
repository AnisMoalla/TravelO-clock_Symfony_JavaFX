<?php

namespace App\Repository;

use App\Entity\AvisFacc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AvisFacc|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvisFacc|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvisFacc[]    findAll()
 * @method AvisFacc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisFaccRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvisFacc::class);
    }

    // /**
    //  * @return AvisFacc[] Returns an array of AvisFacc objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AvisFacc
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
