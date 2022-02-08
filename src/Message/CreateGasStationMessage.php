<?php

namespace App\Message;

use App\EntityId\GasStationId;

class CreateGasStationMessage
{
    /** @var GasStationId */
    private $gasStationId;

    /** @var string */
    private $pop;

    /** @var string */
    private $cp;

    /** @var string|null */
    private $longitude;

    /** @var string|null */
    private $latitude;

    /** @var string */
    private $street;

    /** @var string */
    private $city;

    /** @var string */
    private $country;

    /** @var array */
    private $element;

    public function __construct(GasStationId $gasStationId, string $pop, string $cp, $longitude, $latitude, string $street, string $city, string $country, array $element) {
        $this->gasStationId = $gasStationId;
        $this->pop = $pop;
        $this->cp = $cp;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->street = $street;
        $this->city = $city;
        $this->country = $country;
        $this->element = $element;
    }

    public function getGasStationId(): GasStationId
    {
        return $this->gasStationId;
    }

    public function getPop(): string
    {
        return $this->pop;
    }

    public function getCp(): string
    {
        return $this->cp;
    }

    /** @return string|null */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /** @return string|null */
    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getElement(): array
    {
        return $this->element;
    }
}