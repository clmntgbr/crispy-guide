<?php

namespace App\Message;

use App\EntityId\GasStationId;

class CreateGooglePlaceMessage
{
    /** @var GasStationId */
    private $gasStationId;

    /** @var string */
    private $placeId;

    public function __construct(GasStationId $gasStationId, string $placeId)
    {
        $this->gasStationId = $gasStationId;
        $this->placeId = $placeId;
    }

    public function getGasStationId(): GasStationId
    {
        return $this->gasStationId;
    }

    public function getPlaceId(): string
    {
        return $this->placeId;
    }
}