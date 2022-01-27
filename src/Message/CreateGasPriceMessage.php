<?php

namespace App\Message;

use App\EntityId\GasStationId;
use App\EntityId\GasTypeId;

class CreateGasPriceMessage
{
    /** @var GasStationId */
    private $gasStationId;

    /** @var GasTypeId */
    private $gasTypeId;

    /** @var string */
    private $date;

    /** @var string */
    private $value;

    public function __construct(GasStationId $gasStationId, GasTypeId $gasTypeId, string $date, string $value)
    {
        $this->gasStationId = $gasStationId;
        $this->gasTypeId = $gasTypeId;
        $this->date = $date;
        $this->value = $value;
    }

    public function getGasStationId(): GasStationId
    {
        return $this->gasStationId;
    }

    public function getGasTypeId(): GasTypeId
    {
        return $this->gasTypeId;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getValue(): string
    {
        return $this->value;
    }


}