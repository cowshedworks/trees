<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class Age extends UnitValue
{
    protected $value;
    protected $unit;

    protected function setValue($value)
    {
        return $this->value = (float) $value;
    }
}
