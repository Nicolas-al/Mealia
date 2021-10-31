<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findEntitiesByName($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p.name
                FROM App\Entity\Product p
                WHERE p.name
                LIKE :nam'
            )
            ->setParameter('nam', '%'.$str.'%')
            // ->setParameter('collectio', '%'.$str.'%')
            ->getResult();
    }

    public function findEntitiesByCollection($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM App\Entity\Product p
                INNER JOIN p.collection c
                WHERE c.name
                LIKE :collectio'
            )
            ->setParameter('collectio', '%'.$str.'%')
            // ->setParameter('collectio', '%'.$str.'%')
            ->getResult();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
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
