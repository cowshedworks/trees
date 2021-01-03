<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Calculators;

use CowshedWorks\Trees\UnitValues\Weight;

class TotalCarbonWeight extends CalculatorAbstract
{    
    public function calculate(Weight $totalDryWeight): Weight
    {
        return $this->unitValueFactory->weight(
            $totalDryWeight->getValueIn('lbs') * 0.5,
            'lbs'
        );
    }
}