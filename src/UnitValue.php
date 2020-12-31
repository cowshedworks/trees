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

    protected function setupUnitValue($constructValue, $constructUnit): void
    {
        if ($constructUnit != $this->getDefault()) {
            // The provided unit is not the default, we need to convert the
            // unit and adjust the value
            $conversionFunction = $this->getConverionFunction($constructUnit, $this->getDefault());

            $constructValue = $conversionFunction($constructValue);
            $constructUnit = $this->getDefault();
        }

        $this->value = round($constructValue, $this->getPrecision());
        $this->unit = $constructUnit;
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
        return static::DEFAULT_UNIT;
    }

    public static function getPrecision()
    {
        return static::PRECISION;
    }

    protected function getConverionFunction($fromUnit, $toUnit)
    {
        $conversionString = $fromUnit.'-'.$toUnit;

        switch ($conversionString) {
            case 'months-years':
                return function ($value) {
                    return $value / 12;
                };
                break;
            case 'days-years':
                return function ($value) {
                    return $value / 365;
                };
                break;
            case 'mm-cm':
                return function ($value) {
                    return $value / 10;
                };
                break;
        }

        throw new Exception("Cannot convert {$conversionString}");
    }
}
