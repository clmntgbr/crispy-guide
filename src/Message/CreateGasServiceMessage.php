<?php

namespace App\Message;

use App\EntityId\GasStationId;

class CreateGasServiceMessage
{
    /** @var GasStationId */
    private $gasStationId;

    /** @var string */
    private $label;

    public function __construct(GasStationId $gasStationId, string $label)
    {
        $this->gasStationId = $gasStationId;
        $this->label = $label;
    }

    public function getGasStationId(): GasStationId
    {
        return $this->gasStationId;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}