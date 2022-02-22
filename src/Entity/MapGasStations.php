<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Api\GetMapGasStations;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get_map_gas_stations" = {
 *              "method"="GET",
 *              "path"="/map/gas_stations",
 *              "controller"=GetMapGasStations::class,
 *              "pagination_enabled"=false
 *          }
 *     },
 *     itemOperations={}
 * )
 */
class MapGasStations
{
    public $id;
}
