<?php

namespace App\Repository;

use App\Entity\GEdgeType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GEdgeType|null find($id, $lockMode = null, $lockVersion = null)
 * @method GEdgeType|null findOneBy(array $criteria, array $orderBy = null)
 * @method GEdgeType[]    findAll()
 * @method GEdgeType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GEdgeTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GEdgeType::class);
    }

    // /**
    //  * @return GEdgeType[] Returns an array of GEdgeType objects
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
    public function findOneBySomeField($value): ?GEdgeType
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
