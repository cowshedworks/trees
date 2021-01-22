<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Calculators;

use CowshedWorks\Trees\UnitValues\Diameter;
use CowshedWorks\Trees\UnitValues\Height;
use CowshedWorks\Trees\UnitValues\Weight;

class AboveGroundWeightCalculator extends CalculatorAbstract
{
    private function getDiameterCoefficient(float $diameter): float
    {
        if ($diameter < 27.94) {
            return 0.25;
        }

        return 0.15;
    }

    public function calculate(Diameter $diameter, Height $height): Weight
    {
        return $this->unitValueFactory->weight(
            $this->getDiameterCoefficient($diameter->getValue()) * pow($diameter->getValueIn('in'), 2) * $height->getValueIn('ft'),
            // 0.104 * (pow(pow($diameter->getValueIn('in'), 2), 1.17) * pow($height->getValueIn('ft'), 0.93)),
            'lbs'
        );
    }
}
