<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Calculators;

use CowshedWorks\Trees\UnitValues\Weight;

class TotalBelowGroundWeightCalculator extends CalculatorAbstract
{    
    public function calculate(Weight $aboveGroundWeight): Weight
    {
        return $this->unitValueFactory->weight(
            $aboveGroundWeight->getValueIn('lbs') * 0.2,
            'lbs'
        );
    }
}