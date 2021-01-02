<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

use CowshedWorks\Trees\UnitValues\Height;
use Exception;

class UnitValueFactory
{
    protected array $unitValues = [
        'age' => Age::class,
        'height' => Height::class,
        'lenth' => Length::class,
        'weight' => Weight::class,
    ];

    public function __construct()
    {

    }

    public function __call($method, $params)
    {
        $unitValueData = $params[0];
        return new $this->unitValues[$method]($unitValueData['value'], $unitValueData['unit']);
    }
}
