<?php

namespace App\Repository;

use App\Entity\ReservationFacceuil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReservationFacceuil|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationFacceuil|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationFacceuil[]    findAll()
 * @method ReservationFacceuil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationFacceuilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationFacceuil::class);
    }

    // /**
    //  * @return ReservationFacceuil[] Returns an array of ReservationFacceuil objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReservationFacceuil
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
