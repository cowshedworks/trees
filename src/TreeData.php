<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

use CowshedWorks\Trees\UnitValues\Age;
use CowshedWorks\Trees\UnitValues\Circumference;
use CowshedWorks\Trees\UnitValues\Diameter;
use CowshedWorks\Trees\UnitValues\Height;
use CowshedWorks\Trees\UnitValues\Weight;

class TreeData
{
    public static array $requiredvalidParameters = ['circumference', 'age', 'height', 'diameter'];
    private array $speciesData;
    private $age;
    private $circumference;
    private $diameter;
    private $height;
    private $aboveGroundWeight;
    private $belowGroundWeight;
    private $totalGreenWeight;
    private $totalDryWeight;
    private $totalCarbonWeight;
    private $totalCarbonSequestered;

    public function __construct(array $speciesData, array $treeData)
    {
        $this->speciesData = $speciesData;
        $this->init($treeData);
    }

    private function init(array $treeData): void
    {
        $this->buildProvidedAttributes($treeData);
        $this->buildEmptyAttributes();
        $this->calculateWeights();
    }

    private function buildProvidedAttributes(array $treeData): void
    {
        foreach (self::$requiredvalidParameters as $parameter) {
            if (false === isset($treeData[$parameter])) {
                continue;
            }

            $values = [];

            if (isset($parameter)) {
                $values = preg_split('/(?<=[0-9])(?=[a-z]+)/i', $treeData[$parameter]);
            }

            $unitValueClass = '\CowshedWorks\Trees\UnitValues\\'.ucfirst($parameter);

            if (count($values) === 2) {
                $this->{$parameter} = new $unitValueClass($values[0], $values[1]);
            }

            if (count($values) === 1) {
                $this->{$parameter} = new $unitValueClass($values[0], $unitValueClass::getDefault());
            }
        }
    }

    private function buildEmptyAttributes(): void
    {
        if ($this->age === null) {
            if ($this->circumference) {
                $this->age = new Age($this->circumference->getValue() / $this->getSpeciesData('attributes.growth-rate.annual-average-circumference.value'), 'years');
            }

            if ($this->height) {
                $this->age = new Age($this->height->getValue() / $this->getSpeciesData('attributes.growth-rate.annual-average-height.value'), 'years');
            }
        }

        if ($this->age === null) {
            throw new Exception('Unable to resolve tree age');
        }

        if ($this->circumference === null) {
            if ($this->diameter != null) {
                $this->circumference = new Circumference($this->diameter->getValue() * M_PI, 'cm');
            } else {
                $this->circumference = new Circumference($this->age->getValue() * $this->getSpeciesData('attributes.growth-rate.annual-average-circumference.value'), 'cm');
            }
        }

        if ($this->diameter === null) {
            $this->diameter = new Diameter($this->circumference->getValue() / M_PI, 'cm');
        }

        if ($this->height === null) {
            $this->height = new Height($this->age->getValue() * $this->getSpeciesData('attributes.growth-rate.annual-average-height.value'), 'cm');
        }
    }

    private function calculateWeights(): void
    {
        $this->aboveGroundWeight = new Weight($this->getDiameterCoefficient() * pow($this->diameter->getValueIn('in'), 2) * $this->height->getValueIn('feet'), 'lbs');
        $this->belowGroundWeight = new Weight($this->aboveGroundWeight->getValueIn('lbs') * 0.2, 'lbs');
        $this->totalGreenWeight = new Weight($this->aboveGroundWeight->getValueIn('lbs') + $this->belowGroundWeight->getValueIn('lbs'), 'lbs');
        $this->totalDryWeight = new Weight($this->totalGreenWeight->getValueIn('lbs') * 0.725, 'lbs');
        $this->totalCarbonWeight = new Weight($this->totalDryWeight->getValueIn('lbs') * 0.5, 'lbs');
        $this->totalCarbonSequestered = new Weight(3.6663 * $this->totalCarbonWeight->getValueIn('lbs'), 'lbs');
    }

    public function getDiameterCoefficient(): float
    {
        if ($this->diameter->getValueIn('in') < 11) {
            return 0.25;
        }

        return 0.15;
    }

    public function getAge(): float
    {
        return $this->age->getValue();
    }

    public function getCircumference(): float
    {
        return $this->circumference->getValue();
    }

    public function getHeight(): float
    {
        return $this->height->getValue();
    }

    public function describeAge(): string
    {
        return $this->age->getDescription();
    }

    public function describeCircumference(): string
    {
        return $this->circumference->getDescription();
    }

    public function describeHeight(): string
    {
        return $this->height->getDescription();
    }

    public function getSpeciesData(string $dataName)
    {
        $currentValue = $this->speciesData;

        foreach (explode('.', $dataName) as $key) {
            $currentValue = $currentValue[$key];
        }

        return $currentValue;
    }

    public function getPopularName(): string
    {
        return $this->getSpeciesData('name.popular');
    }

    public function getCommonNames(): array
    {
        return $this->getSpeciesData('name.common');
    }

    public function getScientificName(): array
    {
        return $this->getSpeciesData('name.scientific');
    }

    public function describeCO2SequestrationToDate(): string
    {
        return $this->totalCarbonSequestered->getDescription();
    }

    public function describeCO2SequestrationPerYear(): string
    {
        return round($this->totalCarbonSequestered->getValue() / $this->age->getValue(), 2).' kg / year';
    }

    public static function validateTreeParameters(array $treeParameters): bool
    {
        $totalValidParams = count(self::$requiredvalidParameters);

        $validParamsNotProvided = array_diff(self::$requiredvalidParameters, array_keys($treeParameters));

        return count($validParamsNotProvided) != $totalValidParams;
    }
}
