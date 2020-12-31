<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class Circumference extends UnitValue
{
    const DEFAULT_UNIT = 'cm';

    protected function setupUnitValue($constructValue, $constructUnit): void
    {
        $this->value = (float) $constructValue;
        $this->unit = $constructUnit;
    }
}
