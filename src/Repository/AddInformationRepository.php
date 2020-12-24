<?php

namespace App\Repository;

use App\Entity\AddInformation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AddInformation|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddInformation|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddInformation[]    findAll()
 * @method AddInformation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddInformationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddInformation::class);
    }

    // /**
    //  * @return AddInformation[] Returns an array of AddInformation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AddInformation
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
