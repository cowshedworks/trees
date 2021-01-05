<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Calculators;

use CowshedWorks\Trees\UnitValues\UnitValueFactory;

abstract class CalculatorAbstract
{
    protected UnitValueFactory $unitValueFactory;

    public function __construct()
    {
        $this->unitValueFactory = new UnitValueFactory();
    }
}
