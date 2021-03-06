<?php

namespace App\Lists;

use App\Traits\ListTrait;

class GasStationStatusReference
{
    use ListTrait;

    public const IN_CREATION = "in_creation";
    public const FOUND_IN_TEXTSEARCH = "found_in_textSearch";
    public const NOT_FOUND_IN_TEXTSEARCH = "not_found_in_textSearch";
    public const PLACE_ID_ANOMALY = "place_id_anomaly";
    public const WAITING_VALIDATION = "waiting_validation";
    public const OPEN = "open";
    public const CLOSED = "closed";
}