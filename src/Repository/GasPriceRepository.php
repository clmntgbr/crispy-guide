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
    }

    public function findGasPricesByYear(int $stationId, string $year)
    {
        $dateMin = sprintf('%s-01-01 00:00:00', $year);
        $dateMax = sprintf('%s-12-31 23:59:59', $year);

        $query = "    SELECT p.gas_station_id, p.date, p.gas_type_id, (p.value/1000) value , p.id, (p.date_timestamp*1000) date_timestamp, t.label, t.reference
                      FROM gas_price p 
                      INNER JOIN gas_type t ON p.gas_type_id = t.id
                      WHERE p.gas_station_id = '$stationId' AND (p.date >= '$dateMin' AND  p.date <= '$dateMax')
                      ORDER BY p.gas_type_id, p.date ASC;"
        ;

        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        return $statement->executeQuery()->fetchAllAssociative();
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
