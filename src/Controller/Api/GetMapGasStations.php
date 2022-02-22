<?php

namespace App\Controller\Api;

use App\Service\MapGasStationsService;
use Symfony\Component\HttpFoundation\Request;

class GetMapGasStations
{
    /** @var MapGasStationsService */
    private $mapGasStationsService;

    public function __construct(MapGasStationsService $mapGasStationsService)
    {
        $this->mapGasStationsService = $mapGasStationsService;
    }

    public function __invoke(Request $request, $data): array
    {
       $mapGasStationsDto = $this->mapGasStationsService->getCollectionData($data);

       $this->mapGasStationsService->validateDto($mapGasStationsDto);

       return $this->mapGasStationsService->getData($mapGasStationsDto);
    }
}