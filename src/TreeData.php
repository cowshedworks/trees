<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

use CowshedWorks\Trees\Calculators\AboveGroundWeightCalculator;
use CowshedWorks\Trees\Calculators\TotalCarbonSequesteredPerYearCalculator;
use CowshedWorks\Trees\Strategies\AgeFromCircumference;
use CowshedWorks\Trees\Strategies\AgeFromHeight;
use CowshedWorks\Trees\Strategies\CircumferenceFromDiameter;
use CowshedWorks\Trees\Strategies\CircumferenceFromGrowthRate;
use CowshedWorks\Trees\Strategies\DiameterFromCircumference;
use CowshedWorks\Trees\Strategies\HeightFromGrowthRate;
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

    private array $strategies = [];

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

    private UnitValueFactory $unitValueFactory;

    public function __construct(array $speciesData, array $treeData)
    {
        $this->speciesData = $speciesData;
        $this->unitValueFactory = new UnitValueFactory();
        $this->resolveProvidedAttributes($treeData);
        $this->resolveStrategies();
        $this->executeStrategies();
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

    public function setAge(Age $age): void
    {
        $this->age = $age;
    }

    public function getAge(): Age
    {
        return $this->age;
    }

    public function setCircumference(Circumference $circumference): void
    {
        $this->circumference = $circumference;
    }

    public function getCircumference(): Circumference
    {
        return $this->circumference;
    }

    public function setHeight(Height $height): void
    {
        $this->height = $height;
    }

    public function getHeight(): Height
    {
        return $this->height;
    }

    public function setDiameter(Diameter $diameter): void
    {
        $this->diameter = $diameter;
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
        $this->aboveGroundWeight = (new AboveGroundWeightCalculator)->calculate($this->getDiameter(), $this->getHeight());
    }

    private function calculateTotalCarbonSequesteredPerYear(): void
    {
        $this->totalCarbonSequesteredPerYear = (new TotalCarbonSequesteredPerYearCalculator)->calculate($this->getAge(), $this->totalCarbonSequestered);
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

    private function resolveProvidedAttributes(array $treeData): void
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

    private function executeStrategies(): void
    {
        foreach ($this->strategies as $strategy) {
            $strategy->run($this);
        }
    }

    private function resolveAgeStrategy(): void
    {
        if ($this->circumference) {
            $this->strategies[] = new AgeFromCircumference();
            return;
        }

        if ($this->height) {
            $this->strategies[] = new AgeFromHeight();
            return;
        }

        // We're unable to calculate the age, this is fatal
        throw new Exception('Unable to resolve tree age, unable to continue');
    }

    private function resolveCircumferenceStrategy(): void
    {
        if ($this->diameter != null) {
            $this->strategies[] = new CircumferenceFromDiameter();
            return;
        }

        $this->strategies[] = new CircumferenceFromGrowthRate();
    }

    private function resolveStrategies(): void
    {
        if ($this->age === null) {
            $this->resolveAgeStrategy();
        }

        if ($this->circumference === null) {
            $this->resolveCircumferenceStrategy();
        }

        if ($this->diameter === null) {
            $this->strategies[] = new DiameterFromCircumference();
        }

        if ($this->height === null) {
            $this->strategies[] = new HeightFromGrowthRate();
        }
    }
}
