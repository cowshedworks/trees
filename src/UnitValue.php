<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

abstract class UnitValue
{
    protected $value;
    protected $unit;

    public function __construct($value, $unit)
    {
        $this->value = $this->setValue($value);
        $this->unit = $this->setUnit($unit);
    }

    abstract protected function setValue($value);

    public function setUnit($unit)
    {
        return $this->unit = (string) $unit;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getUnit()
    {
        return $this->unit;
    }

    public function getDescription()
    {
        return "{$this->getValue()} {$this->getUnit()}";
    }

    public static function getDefault()
    {
        return static::DEFAULTUNIT;
    }
}
