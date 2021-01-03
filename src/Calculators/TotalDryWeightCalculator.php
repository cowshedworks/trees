<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Calculators;

use CowshedWorks\Trees\UnitValues\Weight;

class TotalDryWeightCalculator extends CalculatorAbstract
{    
    public function calculate(Weight $totalGreenWeight): Weight
    {
        return $this->unitValueFactory->weight(
            $totalGreenWeight->getValueIn('lbs') * 0.725,
            'lbs'
        );
    }
}