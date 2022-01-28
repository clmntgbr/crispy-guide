<?php

namespace App\Message;

use App\EntityId\GasStationId;

class CreateGooglePlaceDetailsMessage
{
    /** @var GasStationId */
    private $gasStationId;

    public function __construct(GasStationId $gasStationId)
    {
        $this->gasStationId = $gasStationId;
    }

    public function getGasStationId(): GasStationId
    {
        return $this->gasStationId;
    }
}