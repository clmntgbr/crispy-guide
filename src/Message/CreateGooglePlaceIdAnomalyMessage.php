<?php

namespace App\Message;

use App\EntityId\GasStationId;

class CreateGooglePlaceIdAnomalyMessage
{
    /** @var GasStationId[] */
    private $gasStationIds;

    /** @param GasStationId[] $gasStationIds */
    public function __construct($gasStationIds)
    {
        $this->gasStationIds = $gasStationIds;
    }

    /** @var GasStationId[] */
    public function getGasStationIds()
    {
        return $this->gasStationIds;
    }
}