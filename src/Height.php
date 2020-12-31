<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class Height extends UnitValue
{
    const DEFAULT_UNIT = 'cm';

    protected function setupUnitValue($constructValue, $constructUnit): void
    {
        $this->value = (int) $constructValue;
        $this->unit = $constructUnit;
    }
}
