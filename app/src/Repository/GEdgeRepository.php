<?php

namespace App\Repository;

use App\Entity\GEdge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GEdge|null find($id, $lockMode = null, $lockVersion = null)
 * @method GEdge|null findOneBy(array $criteria, array $orderBy = null)
 * @method GEdge[]    findAll()
 * @method GEdge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GEdgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GEdge::class);
    }

    // /**
    //  * @return GEdge[] Returns an array of GEdge objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GEdge
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
