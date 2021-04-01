<?php

namespace App\Repository;

use App\Entity\Badword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Badword|null find($id, $lockMode = null, $lockVersion = null)
 * @method Badword|null findOneBy(array $criteria, array $orderBy = null)
 * @method Badword[]    findAll()
 * @method Badword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BadwordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Badword::class);
    }

    // /**
    //  * @return Badword[] Returns an array of Badword objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Badword
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
