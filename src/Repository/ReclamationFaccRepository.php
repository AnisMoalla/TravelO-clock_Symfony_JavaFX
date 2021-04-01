<?php

namespace App\Repository;

use App\Entity\ReclamationFacc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReclamationFacc|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReclamationFacc|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReclamationFacc[]    findAll()
 * @method ReclamationFacc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationFaccRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReclamationFacc::class);
    }

    // /**
    //  * @return ReclamationFacc[] Returns an array of ReclamationFacc objects
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
    public function findOneBySomeField($value): ?ReclamationFacc
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @return void
     */
    public function countbydate()
    {
        $query = $this->createQueryBuilder('a')
            ->select('SUBSTRING(a.date_reclamation,1,7)as date_reclamation , count(a) as count')
            ->groupBy('date_reclamation');
        return $query->getQuery()->getResult();

    }
}
