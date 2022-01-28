<?php

namespace App\Repository;

use App\Entity\GasStationStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GasStationStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method GasStationStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method GasStationStatus[]    findAll()
 * @method GasStationStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GasStationStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GasStationStatus::class);
    }

    // /**
    //  * @return GasStationStatus[] Returns an array of GasStationStatus objects
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
    public function findOneBySomeField($value): ?GasStationStatus
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
