<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

use DateTime;
use Exception;

class TreeDataFactory
{
    public static array $requiredParameters = [
        'circumference',
        'height',
    ];

    protected array $availableTrees = [];
    protected array $setParameters = [];
    protected DataLoaderInterface $speciesDataLoader;

    public function __construct(DataLoaderInterface $speciesDataLoader = null)
    {
        $this->speciesDataLoader = $speciesDataLoader ?? new SpeciesDataLoader();
        $this->availableTrees = $this->speciesDataLoader->loadAvailableTrees();
    }

    public function getTrees(): array
    {
        return $this->availableTrees;
    }

    public function build(string $treeName, array $userParameters = [], DateTime $today = null): TreeData
    {
        $userParameters = array_merge($this->setParameters, $userParameters);

        $this->checkCanBuild($treeName, $userParameters);

        return $this->buildFromSpeciesDataFile(
            $this->speciesDataLoader->getDataFor($treeName),
            $userParameters,
            $today
        );
    }

    public function buildFromSpeciesDataFile(SpeciesData $treeConfigData, array $userParameters, DateTime $today = null): TreeData
    {
        if ($today === null) {
            $today = new DateTime();
        }

        return new TreeData(
            $treeConfigData,
            $userParameters,
            $today
        );
    }

    public function getSpeciesFileData(string $treeSpecies)
    {
        return $this->speciesDataLoader->getDataFor($treeSpecies);
    }

    public function __call($method, array $args)
    {
        if ($this->isARequiredParameter($method)) {
            $this->setParameters[$method] = $args[0];

            return $this;
        }
    }

    private function checkCanBuild(string $treeName, array $userParameters): void
    {
        if (count($userParameters) < 1) {
            throw new Exception("Cannot build {$treeName} data without at least these parameters: height, circumference");
        }

        if (false == $this->validateTreeParameters($userParameters)) {
            throw new Exception("Cannot build {$treeName} data without at least these parameters: height, circumference");
        }

        if (count($this->availableTrees) === 0) {
            throw new Exception('Config files not loaded');
        }

        if (false == in_array($treeName, $this->availableTrees)) {
            throw new Exception("{$treeName} tree not available");
        }
    }

    private function validateTreeParameters(array $treeParameters): bool
    {
        $missingRequiredParameters = array_diff(self::$requiredParameters, array_keys($treeParameters));

        return count($missingRequiredParameters) === 0;
    }

    private function isARequiredParameter($value): bool
    {
        return array_search($value, self::$requiredParameters) !== false;
    }
}
