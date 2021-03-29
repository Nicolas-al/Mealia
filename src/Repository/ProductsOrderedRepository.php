<?php

namespace App\Repository;

use App\Entity\ProductsOrdered;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductsOrdered|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductsOrdered|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductsOrdered[]    findAll()
 * @method ProductsOrdered[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsOrderedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductsOrdered::class);
    }

    // /**
    //  * @return ProductsOrdered[] Returns an array of ProductsOrdered objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductsOrdered
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
