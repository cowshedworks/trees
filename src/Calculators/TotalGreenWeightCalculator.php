<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Calculators;

use CowshedWorks\Trees\UnitValues\Weight;

class TotalGreenWeightCalculator extends CalculatorAbstract
{
    public function calculate(Weight $aboveGroundWeight, Weight $belowGroundWeight): Weight
    {
        return $this->unitValueFactory->weight(
            $aboveGroundWeight->getValueIn('lbs') + $belowGroundWeight->getValueIn('lbs'),
            'lbs'
        );
    }
}
