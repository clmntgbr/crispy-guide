<?php

namespace App\Service;

use App\Dto\MapGasStationsDto;
use App\Entity\GasStation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MapGasStationsService
{
    /** @var ValidatorInterface */
    private $validator;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $this->validator = $validator;
        $this->em = $em;
    }

    public function getData(MapGasStationsDto $mapGasStationsDto)
    {
        $gasStations = $this->em->getRepository(GasStation::class)->getGasStationsForMap(
            $mapGasStationsDto->longitude,
            $mapGasStationsDto->latitude,
            $mapGasStationsDto->radius,
            []
        );

        dump($gasStations);
        die;
    }

    /**
     * @return bool|\Exception
     */
    public function validateDto(MapGasStationsDto $mapGasStationsDto)
    {
        $errors = $this->validator->validate($mapGasStationsDto);

        if (count($errors) > 0) {
            throw new \Exception(sprintf('MapGasStations errors : %s',(string) $errors));
        }

        return true;
    }

    public function getCollectionData(array $data): MapGasStationsDto
    {
        $mapGasStations = new MapGasStationsDto();
        $mapGasStations->longitude = $data['longitude'] ?? null;
        $mapGasStations->latitude = $data['latitude'] ?? null;
        $mapGasStations->radius = $data['radius'] ?? null;

        return $mapGasStations;
    }
}