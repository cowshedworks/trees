<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\UnitValueFactory;
use CowshedWorks\Trees\UnitValues\Age;

class AgeFromCircumference extends StrategyAbstract
{
    protected UnitValueFactory $unitValueFactory;

    public function __construct()
    {
        $this->unitValueFactory = new UnitValueFactory();
    }

    public function run(): Age
    {
        
    }
}
