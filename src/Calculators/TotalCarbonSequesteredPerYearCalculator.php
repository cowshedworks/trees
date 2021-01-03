<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Calculators;

use CowshedWorks\Trees\UnitValues\Age;
use CowshedWorks\Trees\UnitValues\Weight;

class TotalCarbonSequesteredPerYearCalculator extends CalculatorAbstract
{    
    public function calculate(Age $age, Weight $totalCarbonSequestered): Weight
    {
        return $this->unitValueFactory->weight(
            $totalCarbonSequestered->getValue() / $age->getValue(),
            'kg'
        );
    }
}