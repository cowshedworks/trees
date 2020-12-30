<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class Age extends UnitValue
{
    const DEFAULTUNIT = 'years';

    protected $value;
    protected $unit;

    protected function setValue($value)
    {
        return $this->value = round((float) $value, 2);
    }
}
