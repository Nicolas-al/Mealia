<?php

namespace App\Repository;

use App\Entity\ZeroStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ZeroStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method ZeroStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method ZeroStock[]    findAll()
 * @method ZeroStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZeroStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZeroStock::class);
    }

    // /**
    //  * @return ZeroStock[] Returns an array of ZeroStock objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('z.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ZeroStock
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
