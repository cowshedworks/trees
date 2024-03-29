<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\UnitValues;

use Exception;

abstract class UnitValue
{
    const DEFAULT_UNIT = null;
    const PRECISION = null;

    protected float $value;
    protected float $constructValue;
    protected $unit;
    protected $constructUnit;

    public function __construct($constructValue, $constructUnit)
    {
        $this->constructValue = (float) $constructValue;
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

    public function describe()
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
            case 'days-years':
                return function ($value) {
                    return $value / 365;
                };
            case 'mm-cm':
                return function ($value) {
                    return $value / 10;
                };
            case 'm-cm':
                return function ($value) {
                    return $value * 100;
                };
            case 'cm-m':
                return function ($value) {
                    return $value / 100;
                };
            case 'in-cm':
                return function ($value) {
                    return $value * 2.54;
                };
            case 'cm-in':
                return function ($value) {
                    return $value / 2.54;
                };
            case 'lbs-kg':
                return function ($value) {
                    return $value * 0.453592;
                };
            case 'kg-lbs':
                return function ($value) {
                    return $value / 0.453592;
                };
            case 'grams-kg':
                return function ($value) {
                    return $value / 1000;
                };
            case 'ft-cm':
                return function ($value) {
                    return $value * 30.48;
                };
            case 'cm-ft':
                return function ($value) {
                    return $value / 30.48;
                };
        }

        throw new Exception("Cannot convert {$conversionString}");
    }

    public function getValueIn($unit): float
    {
        $conversionFunction = $this->getConverionFunction($this->getDefault(), $unit);

        return $conversionFunction($this->getValue());
    }

    public function __toString(): string
    {
        return $this->describe();
    }
}
