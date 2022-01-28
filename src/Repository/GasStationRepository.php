<?php

namespace App\Repository;

use App\Entity\GasStation;
use App\Lists\GasStationStatusReference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GasStation|null find($id, $lockMode = null, $lockVersion = null)
 * @method GasStation|null findOneBy(array $criteria, array $orderBy = null)
 * @method GasStation[]    findAll()
 * @method GasStation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GasStationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GasStation::class);
    }

    public function findGasStationById()
    {
        $query = $this->createQueryBuilder('s')
            ->select('s.id')
            ->orderBy('s.id', 'ASC')
            ->indexBy('s', 's.id')
            ->getQuery();

        return $query->getResult();
    }

    public function getGasStationsForDetails()
    {
        $query = $this->createQueryBuilder('s')
            ->select('s')
            ->innerJoin('s.gasStationStatus', 'ss')
            ->where('ss.label = :label')
            ->setParameter('label', GasStationStatusReference::IN_CREATION)
            ->setMaxResults(25)
            ->getQuery();

        return $query->getResult();
    }

    public function getGasStationGooglePlaceByPlaceId(string $placeId)
    {
        $query = $this->createQueryBuilder('s')
            ->select('s')
            ->innerJoin('s.googlePlace', 'ss')
            ->where('ss.placeId = :placeId')
            ->setParameter('placeId', $placeId)
            ->getQuery();

        return $query->getResult();
    }

    public function findGasServiceByGasStationId()
    {
        $query = "SELECT s.label, t.id
            FROM gas_stations_services gs
            INNER JOIN gas_service s ON gs.gas_service_id = s.id
            INNER JOIN gas_station t ON gs.gas_station_id = t.id";

        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $results = $statement->executeQuery()->fetchAllAssociative();

        $data = [];
        foreach ($results as $result) {
            $data[$result['id']][$result['label']] = uniqid();
        }

        return $data;
    }
}
