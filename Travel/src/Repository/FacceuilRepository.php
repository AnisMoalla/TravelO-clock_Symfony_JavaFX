<?php

namespace App\Repository;

use App\Entity\Facceuil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Facceuil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facceuil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facceuil[]    findAll()
 * @method Facceuil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacceuilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facceuil::class);
    }

    // /**
    //  * @return Facceuil[] Returns an array of Facceuil objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Facceuil
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
