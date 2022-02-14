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

    public function findGasStationStatusClosed()
    {
        $query = $this->createQueryBuilder('s')
            ->select('s')
            ->innerJoin('s.gasStationStatus', 'ss')
            ->where('ss.reference != :reference')
            ->setParameter('reference', GasStationStatusReference::CLOSED)
            ->getQuery();

        return $query->getResult();
    }

    public function getGasStationsForDetails()
    {
        $query = $this->createQueryBuilder('s')
            ->select('s')
            ->innerJoin('s.gasStationStatus', 'ss')
            ->where('ss.reference = :reference')
            ->setParameter('reference', GasStationStatusReference::IN_CREATION)
            ->setMaxResults(15) //TODO REMOVE FOR PROD
            ->getQuery();

        return $query->getResult();
    }

    public function getGasStationsForMap(string $longitude, string $latitude, string $radius)
    {
        $query = "SELECT s.id as gas_station_id, s.address_id, s.company, s.name, s.last_gas_prices, s.previous_gas_prices, s.gas_station_status_id, s.google_place_id, a.*, (SQRT(POW(69.1 * (a.latitude - $latitude), 2) + POW(69.1 * ($longitude - a.longitude) * COS(a.latitude / 57.3), 2))*1000) as distance
                  FROM gas_station s
                  INNER JOIN address a ON s.address_id = a.id
                  WHERE a.longitude IS NOT NULL AND a.latitude IS NOT NULL AND s.closed_at IS NULL AND SUBSTRING(a.postal_code, 1, 2) = '94'
                  HAVING `distance` < $radius
                  ORDER BY `distance` ASC LIMIT 300;";

        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        return $statement->executeQuery()->fetchAllAssociative();
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
