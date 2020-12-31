<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class Age extends UnitValue
{
    const DEFAULT_UNIT = 'years';

    protected function setupUnitValue($constructValue, $constructUnit): void
    {
        if ($constructUnit != $this->getDefault()) {
            // The provided unit is not the default, we need to convert the
            // unit and adjust the value

            $conversionFunction = $this->getConverionMultiplier($constructUnit, $this->getDefault());

            $this->value = round((float) $conversionFunction($constructValue), 1);
            $this->unit = $this->getDefault();
            return;
        }

        $this->value = round((float) $constructValue, 2);
        $this->unit = $constructUnit;
    }
}
