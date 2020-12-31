<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class Age extends UnitValue
{
    const DEFAULT_UNIT = 'years';

    protected function setupUnitValue($constructValue, $constructUnit): void
    {
        $this->value = round((float) $this->constructValue, 2);
        $this->unit = $constructUnit;
    }
}
