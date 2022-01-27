<?php

namespace App\Lists;

use App\Traits\ListTrait;

class GasTypeReference
{
    use ListTrait;

    public const GAZOLE = [
        'id' => 1,
        'label' => 'Gazole',
        'reference' => 'gazole',
    ];

    public const SP95 = [
        'id' => 2,
        'label' => 'SP95',
        'reference' => 'sp95',
    ];

    public const E85 = [
        'id' => 3,
        'label' => 'E85',
        'reference' => 'e85',
    ];

    public const GPLC = [
        'id' => 4,
        'label' => 'GPLc',
        'reference' => 'gplc',
    ];

    public const E10 = [
        'id' => 5,
        'label' => 'E10',
        'reference' => 'e10',
    ];

    public const SP98 = [
        'id' => 6,
        'label' => 'SP98',
        'reference' => 'sp98',
    ];
}