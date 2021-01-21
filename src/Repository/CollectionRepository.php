<?php

namespace App\Repository;

use App\Entity\ProductCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Collection|null find($id, $lockMode = null, $lockVersion = null)
 * @method Collection|null findOneBy(array $criteria, array $orderBy = null)
 * @method Collection[]    findAll()
 * @method Collection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCollection::class);
    }

    /**
     * @return Collection[] Returns an array of Collection objects
     */
    
    public function getDistinct(){
        return $this->createQueryBuilder('productcollection')
            ->select('productcollection.id, productcollection.name')
            ->distinct()
            ->getQuery()
            ->getResult();
    }
    
    // /**
    //  * @return Collection[] Returns an array of Collection objects
    //  */

    // public function findByName(array $name)
    // {
    //     return $this->createQueryBuilder('c')
    //         ->where('c.name = :val')
    //         ->setParameter('val', $name)
    //         ->getQuery()
    //         ->getResult();
    // }
    
    
    
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
    // public function findOneBySomeField($value): ?Collection
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }
    
}
