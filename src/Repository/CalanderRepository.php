<?php

namespace App\Repository;

use App\Entity\Calander;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Calander|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calander|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calander[]    findAll()
 * @method Calander[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalanderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calander::class);
    }

    // /**
    //  * @return Calander[] Returns an array of Calander objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Calander
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
