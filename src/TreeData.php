<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

use CowshedWorks\Trees\Calculators\AboveGroundWeightCalculator;
use CowshedWorks\Trees\Calculators\TotalBelowGroundWeightCalculator;
use CowshedWorks\Trees\Calculators\TotalCarbonSequesteredCalculator;
use CowshedWorks\Trees\Calculators\TotalCarbonSequesteredPerYearCalculator;
use CowshedWorks\Trees\Calculators\TotalCarbonWeightCalculator;
use CowshedWorks\Trees\Calculators\TotalDryWeightCalculator;
use CowshedWorks\Trees\Calculators\TotalGreenWeightCalculator;
use CowshedWorks\Trees\Strategies\AgeFromCircumference;
use CowshedWorks\Trees\Strategies\AgeFromHeight;
use CowshedWorks\Trees\Strategies\DiameterFromCircumference;
use CowshedWorks\Trees\Strategies\RecalculateAgeFromObservedAge;
use CowshedWorks\Trees\Strategies\RecalculateCircumferenceFromAgeAndGrowthRate;
use CowshedWorks\Trees\Strategies\RecalculateHeightFromAgeAndGrowthRate;
use CowshedWorks\Trees\UnitValues\Age;
use CowshedWorks\Trees\UnitValues\Circumference;
use CowshedWorks\Trees\UnitValues\Diameter;
use CowshedWorks\Trees\UnitValues\Height;
use CowshedWorks\Trees\UnitValues\Length;
use CowshedWorks\Trees\UnitValues\UnitValueFactory;
use CowshedWorks\Trees\UnitValues\Weight;
use DateTime;
use Exception;

class TreeData
{
    const DEFAULT_HEIGHT_GROWTH_RATE = 60;
    const DEFAULT_CIRCUMFERENCE_GROWTH_RATE = 2.5;
    const DEFAULT_MAX_HEIGHT = 4000;
    const DEFAULT_MAX_CIRCUMFERENCE = 400;

    public static array $requiredParameters = [
        'circumference',
        'height',
    ];

    public static array $acceptedParameters = [
        'circumference',
        'height',
        'age',
        'observed',
    ];

    private array $speciesData;

    private ?Age $age = null;

    private ?Circumference $circumference = null;

    private ?Diameter $diameter = null;

    private ?Height $height = null;

    private ?Weight $aboveGroundWeight = null;

    private ?Weight $belowGroundWeight = null;

    private ?Weight $totalGreenWeight = null;

    private ?Weight $totalDryWeight = null;

    private ?Weight $totalCarbonWeight = null;

    private ?Weight $totalCarbonSequestered = null;

    private ?Weight $totalCarbonSequesteredPerYear = null;

    private ?Height $growthRateHeightActual = null;

    private ?Length $growthRateCircumferenceActual = null;

    private UnitValueFactory $unitValueFactory;

    private array $buildLog = [];

    private DateTime $observedAt;

    private bool $hasOlderObservedAge = false;

    public function __construct(array $speciesData, array $treeData)
    {
        $this->unitValueFactory = new UnitValueFactory();
        $this->speciesData = $speciesData;
        $this->observedAt = new DateTime('midnight');
        $this->resolveProvidedAttributes($treeData);
        $this->executeStrategies();
        $this->calculateRates();
        $this->calculateWeights();
    }

    public function getObservedDate(): DateTime
    {
        return $this->observedAt;
    }

    public function getObservationDateDiffYears(): float
    {
        return (new DateTime())->diff($this->getObservedDate())->days / 365;
    }

    public function hasOlderObservedAge(): bool
    {
        return $this->hasOlderObservedAge;
    }

    public function getBuildLog(): array
    {
        return $this->buildLog;
    }

    public function logBuild(string $logMessage): void
    {
        $this->buildLog[] = $logMessage;
    }

    public function getPopularName(): string
    {
        return $this->getSpeciesData('name.popular');
    }

    public function getFamilyName(): string
    {
        return $this->getSpeciesData('name.family');
    }

    public function getCommonNames(): array
    {
        return $this->getSpeciesData('name.common');
    }

    public function getHabitat(): string
    {
        return $this->getSpeciesData('information.habitat');
    }

    public function getScientificName(): array
    {
        return $this->getSpeciesData('name.scientific');
    }

    public function getMaxHeight(): float
    {
        $maxHeightFromData = $this->getSpeciesData('attributes.dimensions.max-height.value');

        return $maxHeightFromData ?? self::DEFAULT_MAX_HEIGHT;
    }

    public function getMaxCircumference(): float
    {
        $maxCircumferenceFromData = $this->getSpeciesData('attributes.dimensions.max-circumference.value');

        return $maxCircumferenceFromData ?? self::DEFAULT_MAX_CIRCUMFERENCE;
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

    public function getActualAverageCircumferenceGrowthRate(): Length
    {
        return $this->growthRateCircumferenceActual;
    }

    public function getAverageAnnualHeightGrowthRate(): Height
    {
        $heightGrowthRate = $this->getSpeciesData('attributes.growth-rate.annual-average-height.value') ?? self::DEFAULT_HEIGHT_GROWTH_RATE;

        return $this->unitValueFactory->height($heightGrowthRate);
    }

    public function getAverageAnnualCircumferenceGrowthRate(): Length
    {
        $circumferenceGrowthRate = $this->getSpeciesData('attributes.growth-rate.annual-average-circumference.value') ?? self::DEFAULT_CIRCUMFERENCE_GROWTH_RATE;

        return $this->unitValueFactory->length($circumferenceGrowthRate);
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
        try {
            $currentValue = $this->speciesData;

            foreach (explode('.', $dataName) as $key) {
                if (isset($currentValue[$key]) === false) {
                    throw new Exception('Species data key does not exist');
                }
                $currentValue = $currentValue[$key];
            }

            return $currentValue;
        } catch (Exception $exception) {
            return null;
        }
    }

    private function calculateRates(): void
    {
        $this->growthRateHeightActual = $this->unitValueFactory->height(
            $this->height->getValue() / $this->age->getValue()
        );

        $this->growthRateCircumferenceActual = $this->unitValueFactory->length(
            $this->circumference->getValue() / $this->age->getValue()
        );
    }

    private function calculateWeights(): void
    {
        $this->aboveGroundWeight = (new AboveGroundWeightCalculator())
            ->calculate($this->getDiameter(), $this->getHeight());

        $this->belowGroundWeight = (new TotalBelowGroundWeightCalculator())
            ->calculate($this->aboveGroundWeight);

        $this->totalGreenWeight = (new TotalGreenWeightCalculator())
            ->calculate($this->aboveGroundWeight, $this->belowGroundWeight);

        $this->totalDryWeight = (new TotalDryWeightCalculator())
            ->calculate($this->totalGreenWeight);

        $this->totalCarbonWeight = (new TotalCarbonWeightCalculator())
            ->calculate($this->totalDryWeight);

        $this->totalCarbonSequestered = (new TotalCarbonSequesteredCalculator())
            ->calculate($this->totalCarbonWeight);

        $this->totalCarbonSequesteredPerYear = (new TotalCarbonSequesteredPerYearCalculator())
            ->calculate($this->getAge(), $this->totalCarbonSequestered);
    }

    public static function validateTreeParameters(array $treeParameters): bool
    {
        $missingRequiredParameters = array_diff(self::$requiredParameters, array_keys($treeParameters));

        return count($missingRequiredParameters) === 0;
    }

    private function resolveProvidedAttributes(array $treeData): void
    {
        foreach ($treeData as $parameter => $data) {
            if ($treeData[$parameter] == '') {
                unset($treeData[$parameter]);
            }
        }

        foreach (self::$acceptedParameters as $parameter) {
            if (false === isset($treeData[$parameter])) {
                continue;
            }

            if ($parameter === 'observed') {
                $dateString = $treeData['observed'];
                $this->observedAt = (new DateTime($dateString))->settime(0, 0);
                $this->hasOlderObservedAge = $this->observedAt < new DateTime('midnight');
                continue;
            }

            $values = preg_split('/(?<=[0-9])(?=[a-z]+)/i', $treeData[$parameter]);

            if (count($values) === 2) {
                $this->{$parameter} = $this->unitValueFactory->{$parameter}($values[0], $values[1]);
            }

            if (count($values) === 1) {
                $this->{$parameter} = $this->unitValueFactory->{$parameter}($values[0]);
            }

            $this->logBuild(ucfirst($parameter).' set from provided parameters');
        }
    }

    private function resetAttributesForRecalculation(): void
    {
        $this->circumference = null;
        $this->height = null;
        $this->diameter = null;
    }

    private function executeStrategies(): void
    {
        if ($this->age === null) {
            $this->executeAgeStrategy();
        }

        if ($this->hasOlderObservedAge()) {
            $this->executeRecalculateStrategy();
        }

        (new DiameterFromCircumference())->execute($this);
    }

    private function executeRecalculateStrategy(): void
    {
        (new RecalculateAgeFromObservedAge())->execute($this);
        (new RecalculateHeightFromAgeAndGrowthRate())->execute($this);
        (new RecalculateCircumferenceFromAgeAndGrowthRate())->execute($this);
    }

    private function executeAgeStrategy(): void
    {
        if ($this->height) {
            (new AgeFromHeight())->execute($this);

            return;
        }

        // Height should always be present so these aren't needed
        // leaving in place as we may add logic to pick the most
        // accurate based on the tree species

        if ($this->circumference) {
            (new AgeFromCircumference())->execute($this);

            return;
        }

        // We're unable to calculate the age, this is fatal
        throw new Exception('Unable to resolve tree age.');
    }
}
