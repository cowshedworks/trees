<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

use Exception;

class TreeCalculatorFactory
{
    const DATADIR = __DIR__.'/data';

    protected bool $availableTreesLoaded = false;

    protected array $availableTrees = [];

    public function __construct()
    {
        $this->loadAvailableTrees();
    }

    public function getTrees(): array
    {
        return $this->availableTrees;
    }

    private function loadAvailableTrees(): void
    {
        $dataFiles = array_diff(
            scandir(self::DATADIR),
            ['..', '.']
        );

        $dataFiles = array_map(
            function ($filename) {
                return str_replace('.json', '', $filename);
            },
            $dataFiles
        );

        $this->availableTrees = array_values($dataFiles);
        $this->availableTreesLoaded = true;
    }

    private function checkCanBuild(string $treeName, array $userParameters): void
    {
        try {
            $configParameter = $userParameters[0];
        } catch (Exception $exception) {
            throw new Exception('No config provided');
        }

        if (false === is_array($configParameter) || count($configParameter) < 1) {
            throw new Exception('Cannot build alder calculator without one of these parameters: age, height, circumference');
        }

        if (false === TreeAttributes::validateTreeParameters($configParameter)) {
            throw new Exception('Cannot build alder calculator without one of these parameters: age, height, circumference');
        }

        if (false === $this->availableTreesLoaded) {
            throw new Exception('Config files not loaded');
        }

        if (false === in_array($treeName, $this->availableTrees)) {
            throw new Exception("{$treeName} not available");
        }
    }

    private function getConfigFor(string $treeName): array
    {
        return json_decode(
            file_get_contents(
                sprintf(
                    '%s/%s.json',
                    self::DATADIR,
                    $treeName
                )
            ),
            true // JSON OBJECT -> ASSOCIATIVE ARRAY
        );
    }

    private function build(string $treeName, array $userParameters): TreeCalculator
    {
        return new TreeCalculator(
            $this->getConfigFor($treeName),
            $userParameters
        );
    }

    public function __call($method, $userParameters)
    {
        $this->checkCanBuild($method, $userParameters);

        return $this->build($method, $userParameters);
    }
}
