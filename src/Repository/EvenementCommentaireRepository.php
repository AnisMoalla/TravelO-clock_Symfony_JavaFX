<?php

namespace App\Repository;

use App\Entity\EvenementCommentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EvenementCommentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvenementCommentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvenementCommentaire[]    findAll()
 * @method EvenementCommentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementCommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvenementCommentaire::class);
    }

    // /**
    //  * @return EvenementCommentaire[] Returns an array of EvenementCommentaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EvenementCommentaire
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
