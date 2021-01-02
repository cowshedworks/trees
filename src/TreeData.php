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
    public static array $requiredvalidParameters = [
        'circumference',
        'age',
        'height',
        'diameter',
    ];

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

    private $totalCarbonSequesteredPerYear;

    private $growthRateHeightActual;

    private $growthRateCircumferenceActual;

    public function __construct(array $speciesData, array $treeData)
    {
        $this->speciesData = $speciesData;
        $this->unitValueFactory = new UnitValueFactory();
        $this->buildProvidedAttributes($treeData);
        $this->resolveEmptyAttributes();
        $this->calculateRates();
        $this->calculateWeights();
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

    public function getMaxAge(): float
    {
        return $this->getSpeciesData('attributes.age.max-average.value');
    }

    public function getAge(): Age
    {
        return $this->age;
    }

    public function getCircumference(): Circumference
    {
        return $this->circumference;
    }

    public function getHeight(): Height
    {
        return $this->height;
    }

    public function getDiameter(): Diameter
    {
        return $this->diameter;
    }

    public function getWeight(): Weight
    {
        return $this->totalGreenWeight;
    }

    public function getCarbonWeight(): Weight
    {
        return $this->totalCarbonWeight;
    }

    public function getCO2SequestrationToDate(): Weight
    {
        return $this->totalCarbonSequestered;
    }

    public function getCO2SequestrationPerYear(): Weight
    {
        return $this->totalCarbonSequesteredPerYear;
    }

    public function getActualAnnualHeightGrowthRate(): Height
    {
        return $this->growthRateHeightActual;
    }

    public function getActualAverageCircumferenceGrowthRate(): Height
    {
        return $this->growthRateCircumferenceActual;
    }

    public function getAverageAnnualHeightGrowthRate(): Height
    {
        return $this->unitValueFactory->height(
            $this->getSpeciesData('attributes.growth-rate.annual-average-height.value')
        );
    }

    public function getAverageAnnualCircumferenceGrowthRate(): Circumference
    {
        return $this->unitValueFactory->circumference(
            $this->getSpeciesData('attributes.growth-rate.annual-average-circumference.value')
        );
    }

    // PRIVATE API
    private function getDiameterCoefficient(float $diameter): float
    {
        if ($diameter < 27.94) {
            return 0.25;
        }

        return 0.15;
    }

    private function getSpeciesDataUnitValue(string $key, string $unitValueClass)
    {
        return $this->unitValueFactory->$unitValueClass(
            $this->getSpeciesData($key)
        );
    }

    private function getSpeciesData(string $dataName)
    {
        $currentValue = $this->speciesData;

        foreach (explode('.', $dataName) as $key) {
            $currentValue = $currentValue[$key];
        }

        return $currentValue;
    }

    private function calculateRates(): void
    {
        $this->growthRateHeightActual = $this->unitValueFactory->height(
            $this->height->getValue() / $this->age->getValue()
        );

        $this->growthRateCircumferenceActual = $this->unitValueFactory->height(
            $this->circumference->getValue() / $this->age->getValue()
        );
    }

    private function calculateWeights(): void
    {
        $this->calculateAboveGroundWeight();
        $this->calculateBelowGroundWeight();
        $this->calculateTotalGreenWeight();
        $this->calculateTotalDryWeight();
        $this->calculateTotalCarbonWeight();
        $this->calculateTotalCarbonSequestered();
        $this->calculateTotalCarbonSequesteredPerYear();
    }

    private function calculateAboveGroundWeight(): void
    {
        $this->aboveGroundWeight = $this->unitValueFactory->weight(
            $this->getDiameterCoefficient($this->diameter->getValue()) * pow($this->diameter->getValueIn('in'), 2) * $this->height->getValueIn('ft'),
            'lbs'
        );
    }

    private function calculateTotalCarbonSequesteredPerYear(): void
    {
        $this->totalCarbonSequesteredPerYear = $this->unitValueFactory->weight(
            $this->totalCarbonSequestered->getValue() / $this->age->getValue(),
            'kg'
        );
    }

    private function calculateTotalCarbonSequestered(): void
    {
        $this->totalCarbonSequestered = $this->unitValueFactory->weight(
            3.6663 * $this->totalCarbonWeight->getValueIn('lbs'),
            'lbs'
        );
    }

    private function calculateTotalCarbonWeight(): void
    {
        $this->totalCarbonWeight = $this->unitValueFactory->weight(
            $this->totalDryWeight->getValueIn('lbs') * 0.5,
            'lbs'
        );
    }

    private function calculateTotalDryWeight(): void
    {
        $this->totalDryWeight = $this->unitValueFactory->weight(
            $this->totalGreenWeight->getValueIn('lbs') * 0.725,
            'lbs'
        );
    }

    private function calculateTotalGreenWeight(): void
    {
        $this->totalGreenWeight = $this->unitValueFactory->weight(
            $this->aboveGroundWeight->getValueIn('lbs') + $this->belowGroundWeight->getValueIn('lbs'),
            'lbs'
        );
    }

    private function calculateBelowGroundWeight(): void
    {
        $this->belowGroundWeight = $this->unitValueFactory->weight(
            $this->aboveGroundWeight->getValueIn('lbs') * 0.2,
            'lbs'
        );
    }

    public static function validateTreeParameters(array $treeParameters): bool
    {
        $totalValidParams = count(self::$requiredvalidParameters);

        $validParamsNotProvided = array_diff(self::$requiredvalidParameters, array_keys($treeParameters));

        return count($validParamsNotProvided) != $totalValidParams;
    }

    private function buildProvidedAttributes(array $treeData): void
    {
        foreach (self::$requiredvalidParameters as $parameter) {
            if (false === isset($treeData[$parameter])) {
                continue;
            }

            $values = preg_split('/(?<=[0-9])(?=[a-z]+)/i', $treeData[$parameter]);

            if (count($values) === 2) {
                $this->{$parameter} = $this->unitValueFactory->{$parameter}($values[0], $values[1]);
            }

            if (count($values) === 1) {
                $this->{$parameter} = $this->unitValueFactory->{$parameter}($values[0]);
            }
        }
    }

    private function resolveEmptyAttributes(): void
    {
        if ($this->age === null) {
            if ($this->circumference) {
                $this->age = $this->unitValueFactory->age($this->circumference->getValue() / $this->getAverageAnnualCircumferenceGrowthRate()->getValue(), 'years');
            }

            if ($this->height) {
                $this->age = $this->unitValueFactory->age($this->height->getValue() / $this->getAverageAnnualHeightGrowthRate()->getValue(), 'years');
            }
        }

        if ($this->age === null) {
            throw new Exception('Unable to resolve tree age');
        }

        if ($this->circumference === null) {
            if ($this->diameter != null) {
                $this->circumference = $this->unitValueFactory->circumference($this->diameter->getValue() * M_PI, 'cm');
            } else {
                $this->circumference = $this->unitValueFactory->circumference($this->age->getValue() * $this->getAverageAnnualCircumferenceGrowthRate()->getValue(), 'cm');
            }
        }

        if ($this->diameter === null) {
            $this->diameter = $this->unitValueFactory->diameter($this->circumference->getValue() / M_PI, 'cm');
        }

        if ($this->height === null) {
            $this->height = $this->unitValueFactory->height($this->age->getValue() * $this->getAverageAnnualHeightGrowthRate()->getValue(), 'cm');
        }
    }
}
