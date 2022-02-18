<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

use CowshedWorks\Trees\Calculators\AboveGroundWeightCalculator;
use CowshedWorks\Trees\Calculators\TotalBelowGroundWeightCalculator;
use CowshedWorks\Trees\Calculators\TotalCarbonSequesteredCalculator;
use CowshedWorks\Trees\Calculators\TotalCarbonSequesteredPerDayCalculator;
use CowshedWorks\Trees\Calculators\TotalCarbonSequesteredPerYearCalculator;
use CowshedWorks\Trees\Calculators\TotalCarbonWeightCalculator;
use CowshedWorks\Trees\Calculators\TotalDryWeightCalculator;
use CowshedWorks\Trees\Calculators\TotalGreenWeightCalculator;
use CowshedWorks\Trees\Regression\HeightAgeRegression;
use CowshedWorks\Trees\Regression\HeightAgeRegressionData;
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
    public static array $acceptedParameters = [
        'circumference',
        'height',
        'observed',
    ];

    private SpeciesData $speciesData;

    private Age $estimatedAge;

    private Circumference $circumference;

    private Diameter $diameter;

    private Height $height;

    private Weight $aboveGroundWeight;

    private Weight $belowGroundWeight;

    private Weight $totalGreenWeight;

    private Weight $totalDryWeight;

    private Weight $totalCarbonWeight;

    private Weight $totalCarbonSequestered;

    private Weight $totalCarbonSequesteredPerYear;

    private Weight $totalCarbonSequesteredPerDay;

    private Height $growthRateHeightActual;

    private Length $growthRateCircumferenceActual;

    private ?HeightAgeRegressionData $heightAgeRegressionData = null;

    private UnitValueFactory $unitValueFactory;

    private array $buildLog = [];

    private DateTime $observedAt;

    private DateTime $today;

    private bool $hasOlderObservedAge = false;

    public function __construct(SpeciesData $speciesData, array $providedTreeData, DateTime $today)
    {
        $this->unitValueFactory = new UnitValueFactory();
        $this->speciesData = $speciesData;
        $this->observedAt = new DateTime('midnight');
        $this->today = $today;

        $this->setupRegressionData();

        $this->setProvidedAttributes($providedTreeData);

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
        return $this->today->diff($this->getObservedDate())->days / 365;
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

        return $maxHeightFromData ?? SpeciesData::DEFAULT_MAX_HEIGHT;
    }

    public function getMaxCircumference(): float
    {
        $maxCircumferenceFromData = $this->getSpeciesData('attributes.dimensions.max-circumference.value');

        return $maxCircumferenceFromData ?? SpeciesData::DEFAULT_MAX_CIRCUMFERENCE;
    }

    public function setEstimatedAge(Age $age): void
    {
        $this->estimatedAge = $age;
    }

    public function getAge(): Age
    {
        // deprecated
        return $this->getEstimatedAge();
    }

    public function getEstimatedAge(): Age
    {
        return $this->estimatedAge;
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

    private function getHeightAgeRegressionData(): HeightAgeRegressionData
    {
        return $this->heightAgeRegressionData;
    }

    public function getHeightAgeRegression(): HeightAgeRegression
    {
        if ($this->hasHeightAgeRegressionData() === false) {
            throw new Exception('No regression data available');
        }

        return new HeightAgeRegression(
            $this->getHeightAgeRegressionData()
        );
    }

    public function hasHeightAgeRegressionData(): bool
    {
        return $this->heightAgeRegressionData != null;
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

    public function getDryWeight(): Weight
    {
        return $this->totalDryWeight;
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

    public function getCO2SequestrationPerDay(): Weight
    {
        return $this->totalCarbonSequesteredPerDay;
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
        $heightGrowthRate = $this->getSpeciesData('attributes.growth-rate.annual-average-height.value') ?? SpeciesData::DEFAULT_HEIGHT_GROWTH_RATE;

        return $this->unitValueFactory->height($heightGrowthRate);
    }

    public function getAverageAnnualCircumferenceGrowthRate(): Length
    {
        $circumferenceGrowthRate = $this->getSpeciesData('attributes.growth-rate.annual-average-circumference.value') ?? SpeciesData::DEFAULT_CIRCUMFERENCE_GROWTH_RATE;

        return $this->unitValueFactory->length($circumferenceGrowthRate);
    }

    // PRIVATE API
    private function getSpeciesData(string $dataName)
    {
        return $this->speciesData->get($dataName);
    }

    private function calculateRates(): void
    {
        $this->growthRateHeightActual = $this->unitValueFactory->height(
            $this->height->getValue() / $this->estimatedAge->getValue()
        );

        $this->growthRateCircumferenceActual = $this->unitValueFactory->length(
            $this->circumference->getValue() / $this->estimatedAge->getValue()
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
            ->calculate($this->getEstimatedAge(), $this->totalCarbonSequestered);

        $this->totalCarbonSequesteredPerDay = (new TotalCarbonSequesteredPerDayCalculator())
            ->calculate($this->getEstimatedAge(), $this->totalCarbonSequesteredPerYear);
    }

    private function setupRegressionData(): void
    {
        $regressionDataForSpecies = $this->getSpeciesData('attributes.growth-rate.height-regression-seed');

        if (null === $regressionDataForSpecies) {
            $this->heightAgeRegressionData = null;

            return;
        }

        $this->heightAgeRegressionData = new HeightAgeRegressionData($regressionDataForSpecies);
    }

    private function setProvidedAttributes(array $providedTreeData): void
    {
        foreach ($providedTreeData as $parameter => $data) {
            if ($providedTreeData[$parameter] == '') {
                unset($providedTreeData[$parameter]);
            }
        }

        foreach (self::$acceptedParameters as $parameter) {
            if (false === isset($providedTreeData[$parameter])) {
                continue;
            }

            if ($parameter === 'observed') {
                $dateString = $providedTreeData['observed'];
                $this->observedAt = (new DateTime($dateString))->settime(0, 0);
                $this->hasOlderObservedAge = $this->observedAt < new DateTime('midnight');
                continue;
            }

            $values = preg_split('/(?<=[0-9])(?=[a-z]+)/i', $providedTreeData[$parameter]);

            if (count($values) === 2) {
                $this->{$parameter} = $this->unitValueFactory->{$parameter}($values[0], $values[1]);
            }

            if (count($values) === 1) {
                $this->{$parameter} = $this->unitValueFactory->{$parameter}($values[0]);
            }

            $this->logBuild(ucfirst($parameter).' set from provided parameters');
        }
    }

    private function executeStrategies(): void
    {
        $this->executeAgeStrategy();

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
