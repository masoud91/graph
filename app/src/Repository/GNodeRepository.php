<?php

namespace App\Repository;

use App\Entity\GNode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GNode|null find($id, $lockMode = null, $lockVersion = null)
 * @method GNode|null findOneBy(array $criteria, array $orderBy = null)
 * @method GNode[]    findAll()
 * @method GNode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GNodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GNode::class);
    }

    // /**
    //  * @return GNode[] Returns an array of GNode objects
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
    public function findOneBySomeField($value): ?GNode
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
