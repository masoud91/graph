<?php

namespace App\Repository;

use App\Entity\GNodeType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GNodeType|null find($id, $lockMode = null, $lockVersion = null)
 * @method GNodeType|null findOneBy(array $criteria, array $orderBy = null)
 * @method GNodeType[]    findAll()
 * @method GNodeType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GNodeTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GNodeType::class);
    }

    // /**
    //  * @return GNodeType[] Returns an array of GNodeType objects
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
    public function findOneBySomeField($value): ?GNodeType
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
