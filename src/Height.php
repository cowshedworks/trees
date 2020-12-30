<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class Height extends UnitValue
{
    const DEFAULTUNIT = 'cm';

    protected $value;
    protected $unit;

    protected function setValue($value)
    {
        return $this->value = (int) $value;
    }
}
