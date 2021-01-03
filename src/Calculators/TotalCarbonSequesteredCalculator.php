<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Calculators;

use CowshedWorks\Trees\UnitValues\Weight;

class TotalCarbonSequesteredCalculator extends CalculatorAbstract
{
    public function calculate(Weight $totalCarbonWeight): Weight
    {
        return $this->unitValueFactory->weight(
            3.6663 * $totalCarbonWeight->getValueIn('lbs'),
            'lbs'
        );
    }
}
