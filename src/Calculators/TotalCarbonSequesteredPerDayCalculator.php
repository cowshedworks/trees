<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Calculators;

use CowshedWorks\Trees\UnitValues\Age;
use CowshedWorks\Trees\UnitValues\Weight;

class TotalCarbonSequesteredPerDayCalculator extends CalculatorAbstract
{
    public function calculate(Age $age, Weight $totalCarbonSequesteredPerYear): Weight
    {
        return $this->unitValueFactory->smallweight(
            $totalCarbonSequesteredPerYear->getValue() / 365,
            'kg'
        );
    }
}
