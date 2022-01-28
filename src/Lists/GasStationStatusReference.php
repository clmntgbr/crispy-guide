<?php

namespace App\Lists;

use App\Traits\ListTrait;

class GasStationStatusReference
{
    use ListTrait;

    public const IN_CREATION = "In Creation";
    public const FOUND_IN_TEXTSEARCH = "Found In TextSearch";
    public const NOT_FOUND_IN_TEXTSEARCH = "Not Found In TextSearch";
    public const PLACE_ID_ANOMALY = "Place Id Anomaly";
    public const WAITING_VALIDATION = "Waiting Validation";
}