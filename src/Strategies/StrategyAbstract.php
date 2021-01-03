<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\UnitValueFactory;
use CowshedWorks\Trees\UnitValues\UnitValue;

abstract class StrategyAbstract
{
    protected UnitValueFactory $unitValueFactory;

    public function __construct()
    {
        $this->unitValueFactory = new UnitValueFactory();
    }

    abstract public function run(): UnitValue;
}
