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
        try {
            $configParameter = $userParameters[0];
        } catch (Exception $exception) {
            throw new Exception('No config provided');
        }

        if (false === is_array($configParameter) || count($configParameter) < 1) {
            throw new Exception('Cannot build alder data without one of these parameters: age, height, circumference');
        }

        if (false === TreeData::validateTreeParameters($configParameter)) {
            throw new Exception('Cannot build alder data without one of these parameters: age, height, circumference');
        }

        if (count($this->availableTrees) === 0) {
            throw new Exception('Config files not loaded');
        }

        if (false === in_array($treeName, $this->availableTrees)) {
            throw new Exception("{$treeName} not available");
        }
    }

    private function build(string $treeName, array $userParameters): TreeData
    {
        return new TreeData(
            $this->configLoader->getConfigFor($treeName),
            $userParameters[0] ?? []
        );
    }

    public function __call($method, $userParameters)
    {
        $this->checkCanBuild($method, $userParameters);

        return $this->build($method, $userParameters);
    }
}
