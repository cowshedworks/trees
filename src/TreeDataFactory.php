<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

use Exception;

class TreeDataFactory
{
    protected array $availableTrees = [];

    public function __construct(ConfigLoader $configLoader = null)
    {
        $this->configLoader = $configLoader ?? new ConfigLoader();
        $this->availableTrees = $this->configLoader->loadAvailableTrees();
    }

    public function getTrees(): array
    {
        return $this->availableTrees;
    }

    private function checkCanBuild(string $treeName, array $userParameters): void
    {
        if (false === is_array($userParameters) || count($userParameters) < 1) {
            throw new Exception("Cannot build {$treeName} data without one of these parameters: age, height, circumference");
        }

        if (false === TreeData::validateTreeParameters($userParameters)) {
            throw new Exception("Cannot build {$treeName} data without one of these parameters: age, height, circumference");
        }

        if (count($this->availableTrees) === 0) {
            throw new Exception('Config files not loaded');
        }

        if (false === in_array($treeName, $this->availableTrees)) {
            throw new Exception("{$treeName} tree not available");
        }
    }

    public function build(string $treeName, array $userParameters): TreeData
    {
        $this->checkCanBuild($treeName, $userParameters);

        return new TreeData(
            $this->configLoader->getConfigFor($treeName),
            $userParameters ?? []
        );
    }

    public function buildFromConfig(array $treeConfigData, array $userParameters): TreeData
    {
        return new TreeData(
            $treeConfigData,
            $userParameters ?? []
        );
    }
    

    public function getSpeciesFileData(string $fileName)
    {
        return $this->configLoader->getConfigFor($fileName);
    }
}
