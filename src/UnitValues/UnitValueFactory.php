<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\UnitValues;

class UnitValueFactory
{
    protected array $unitValues = [
        'age'            => Age::class,
        'circumference'  => Circumference::class,
        'diameter'       => Diameter::class,
        'height'         => Height::class,
        'length'         => Length::class,
        'weight'         => Weight::class,
    ];

    public function __construct()
    {
    }

    public function __call($method, $params)
    {
        if (is_array($params[0])) {
            $unitValueData = $params[0];

            return new $this->unitValues[$method]($unitValueData['value'], $unitValueData['unit']);
        }

        if (count($params) === 2) {
            return new $this->unitValues[$method]($params[0], $params[1]);
        }

        return new $this->unitValues[$method]($params[0], $this->unitValues[$method]::getDefault());
    }
}
