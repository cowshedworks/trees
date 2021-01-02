<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

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

    public function __construct(array $speciesData, array $treeData)
    {
        $this->speciesData = $speciesData;
        $this->unitValueFactory = new UnitValueFactory();
        $this->buildProvidedAttributes($treeData);
        $this->resolveEmptyAttributes();
        $this->calculateWeights();
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

    public function describeDiameter(): string
    {
        return $this->diameter->getDescription();
    }

    public function describeHeight(): string
    {
        return $this->height->getDescription();
    }

    public function describeWeight(): string
    {
        return $this->totalGreenWeight->getDescription();
    }

    public function getSpeciesData(string $dataName)
    {
        $currentValue = $this->speciesData;

        foreach (explode('.', $dataName) as $key) {
            $currentValue = $currentValue[$key];
        }

        return $currentValue;
    }

    public function getSpeciesDataUnitValue(string $key, string $unitValueClass)
    {
        return $this->unitValueFactory->$unitValueClass(
            $this->getSpeciesData($key)
        );
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
        return $this->getSpeciesData('attributes.age.max.value');
    }

    public function getCarbonWeight(): float
    {
        return round($this->totalCarbonWeight->getValue(), 2);
    }

    public function describeCarbonWeight(): string
    {
        return $this->totalCarbonWeight->getDescription();
    }

    public function getCO2SequestrationToDate(): float
    {
        return round($this->totalCarbonSequestered->getValue(), 2);
    }

    public function describeCO2SequestrationToDate(): string
    {
        return $this->totalCarbonSequestered->getDescription();
    }

    public function getCO2SequestrationPerYear(): float
    {
        return round($this->totalCarbonSequesteredPerYear->getValue(), 2);
    }

    public function describeCO2SequestrationPerYear(): string
    {
        return $this->totalCarbonSequesteredPerYear->getDescription();
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

    private function calculateAboveGroundWeight(): void
    {
        $this->aboveGroundWeight = $this->unitValueFactory->weight(
            $this->getDiameterCoefficient() * pow($this->diameter->getValueIn('in'), 2) * $this->height->getValueIn('feet'),
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
                $this->age = $this->unitValueFactory->age($this->circumference->getValue() / $this->getSpeciesData('attributes.growth-rate.annual-average-circumference.value'), 'years');
            }

            if ($this->height) {
                $this->age = $this->unitValueFactory->age($this->height->getValue() / $this->getSpeciesData('attributes.growth-rate.annual-average-height.value'), 'years');
            }
        }

        if ($this->age === null) {
            throw new Exception('Unable to resolve tree age');
        }

        if ($this->circumference === null) {
            if ($this->diameter != null) {
                $this->circumference = $this->unitValueFactory->circumference($this->diameter->getValue() * M_PI, 'cm');
            } else {
                $this->circumference = $this->unitValueFactory->circumference($this->age->getValue() * $this->getSpeciesData('attributes.growth-rate.annual-average-circumference.value'), 'cm');
            }
        }

        if ($this->diameter === null) {
            $this->diameter = $this->unitValueFactory->diameter($this->circumference->getValue() / M_PI, 'cm');
        }

        if ($this->height === null) {
            $this->height = $this->unitValueFactory->height($this->age->getValue() * $this->getSpeciesData('attributes.growth-rate.annual-average-height.value'), 'cm');
        }
    }
}
