<?php

namespace App\Repository;

use App\Entity\GasPrice;
use App\Entity\GasStation;
use App\Entity\GasType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GasPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method GasPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method GasPrice[]    findAll()
 * @method GasPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GasPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GasPrice::class);
    }

    public function findLastGasPriceByTypeAndGasStation(GasStation $gasStation, GasType $gasType)
    {
        return $this->createQueryBuilder('g')
            ->where('g.gasStation = :gs')
            ->andWhere('g.gasType = :gt')
            ->setParameters([
                'gs' => $gasStation,
                'gt' => $gasType,
            ])
            ->orderBy('g.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findLastGasPriceByTypeAndGasStationExceptId(GasStation $gasStation, GasType $gasType, int $gasPriceId)
    {
        return $this->createQueryBuilder('g')
            ->where('g.gasStation = :gs')
            ->andWhere('g.gasType = :gt')
            ->andWhere('g.id != :g')
            ->setParameters([
                'gs' => $gasStation,
                'gt' => $gasType,
                'g' => $gasPriceId
            ])
            ->orderBy('g.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

//        $query = sprintf("SELECT s.id
//                  FROM gas_price s
//                  WHERE s.gas_station_id = %s AND s.gas_type_id = %s AND s.id != %s
//                  ORDER BY s.id DESC LIMIT 1;", $gasStationId, $gasType->getId(), $gasPriceId);
//
//        $statement = $this->getEntityManager()->getConnection()->prepare($query);
//        return $statement->executeQuery()->fetchAssociative();
    }

    public function findLastGasPriceByGasStation(GasStation $gasStation)
    {
        return $this->createQueryBuilder('g')
            ->where('g.gasStation = :gs')
            ->setParameters([
                'gs' => $gasStation,
            ])
            ->orderBy('g.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
