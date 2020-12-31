<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

use Exception;

abstract class UnitValue
{
    protected $value;
    protected $constructValue;
    protected $unit;
    protected $constructUnit;

    public function __construct($constructValue, $constructUnit)
    {
        $this->constructValue = $constructValue;
        $this->constructUnit = $constructUnit;
        $this->setupUnitValue($this->constructValue, $this->constructUnit);
    }

    abstract protected function setupUnitValue($constructValue, $constructUnit);

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
        return static::DEFAULT_UNIT;
    }

    protected function getConverionMultiplier($fromUnit, $toUnit)
    {
        $conversionString = $fromUnit.'-'.$toUnit;

        switch ($conversionString) {
            case "months-years":
                return function($value) {
                    return $value / 12;
                };
                break;
            case "days-years":
                return function($value) {
                    return $value / 365;
                };
                break;
        }

        throw new Exception("Cannot convert {$conversionString}");
    }
}
