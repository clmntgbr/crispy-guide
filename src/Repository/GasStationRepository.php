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
    private $isRadiusNotUsed;

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
            ->setMaxResults(10) //TODO REMOVE FOR PROD
            ->getQuery();

        return $query->getResult();
    }

    private function createGasTypesFilter($filters)
    {
        $query = "";
        if (array_key_exists('gas_types', $filters ?? []) && $filters['gas_types'] !== "") {
            $query = " AND (";
            foreach ($filters['gas_types'] as $gas_type) {
                $query .= "JSON_KEYS(s.last_gas_prices) LIKE '%" . $gas_type . "%' OR ";
            }
            $query = mb_substr($query, 0, -4);
            $query .= ")";
        }
        return $query;
    }

    private function createGasServicesFilter($filters)
    {
        $query = "";
        if (array_key_exists('gas_services', $filters ?? []) && $filters['gas_services'] !== "") {
            $query = " AND (";
            foreach ($filters['gas_services'] as $gas_service) {
                $query .= "`gas_services` LIKE '%" . $gas_service . "%' OR ";
            }
            $query = mb_substr($query, 0, -4);
            $query .= ")";
        }
        return $query;
    }

    private function createGasStationsCitiesFilter($filters)
    {
        $query = "";
        if (array_key_exists('gas_stations_cities', $filters ?? []) && $filters['gas_stations_cities'] !== "") {
            $cities = implode(', ', $filters['gas_stations_cities']);
            $query = " AND a.postal_code IN ($cities)";
            $this->isRadiusNotUsed = true;
        }

        return $query;
    }

    private function createGasStationsDepartmentsFilter($filters)
    {
        $query = "";
        if (array_key_exists('gas_stations_departments', $filters ?? []) && $filters['gas_stations_departments'] !== "") {
            $departments = implode(', ', $filters['gas_stations_departments']);
            $query = " AND SUBSTRING(a.postal_code, 1, 2) IN ($departments)";
            $this->isRadiusNotUsed = true;
        }

        return $query;
    }

    public function getGasStationsForMap(string $longitude, string $latitude, string $radius, $filters)
    {
        $this->isRadiusNotUsed = false;

        $gasTypesFilter = $this->createGasTypesFilter($filters);
        $gasServicesFilter = $this->createGasServicesFilter($filters);
        $gasStationsCitiesFilter = $this->createGasStationsCitiesFilter($filters);
        $gasStationsDepartmentsFilter = $this->createGasStationsDepartmentsFilter($filters);

        if ($this->isRadiusNotUsed) {
            $radius = 100000000000000000;
        }

        $query = "  SELECT s.id as gas_station_id, m.path as preview_path, m.name as preview_name, s.address_id, s.company, 
                    JSON_KEYS(s.last_gas_prices) as gas_types, 
                    s.name as gas_station_name, s.last_gas_prices, s.previous_gas_prices, s.gas_station_status_id, s.google_place_id, a.vicinity,  a.longitude,  a.latitude,
                    p.url,
                    (SQRT(POW(69.1 * (a.latitude - $latitude), 2) + POW(69.1 * ($longitude - a.longitude) * COS(a.latitude / 57.3), 2))*1000) as distance,
                    (SELECT GROUP_CONCAT(gs.label SEPARATOR '<br>')
                    FROM gas_stations_services gss
                    INNER JOIN gas_service gs ON gss.gas_service_id = gs.id
                    AND gss.gas_station_id = s.id) as gas_services
                    FROM gas_station s
                    INNER JOIN address a ON s.address_id = a.id
                    INNER JOIN media m ON s.preview_id = m.id
                    INNER JOIN gas_station_status gs ON s.gas_station_status_id = gs.id
                    LEFT JOIN google_place p ON p.id = s.google_place_id
                    WHERE a.longitude IS NOT NULL AND a.latitude IS NOT NULL AND gs.reference != 'closed' $gasTypesFilter $gasStationsCitiesFilter $gasStationsDepartmentsFilter
                    HAVING `distance` < $radius $gasServicesFilter
                    ORDER BY `distance` ASC LIMIT 300;
        ";

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
