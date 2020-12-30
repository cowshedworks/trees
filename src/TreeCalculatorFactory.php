<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

use CowshedWorks\Trees\TreeCalculator;
use Exception;

class TreeCalculatorFactory
{
    const DATADIR = __DIR__ .'/data';

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

        $dataFiles = array_map(function($filename){
                return str_replace('.json', '', $filename);
            },
        $dataFiles);

        $this->availableTrees = array_values($dataFiles);
        $this->availableTreesLoaded = true;
    }

    private function checkCanBuild(string $treeName): void
    {
        if (false === $this->availableTreesLoaded) {
            throw new Exception("Config files not loaded");
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

    private function build(string $treeName): TreeCalculator
    {
        return new TreeCalculator(
            $this->getConfigFor($treeName)
        );
    }

    public function __call($method, $params)
    {
        $this->checkCanBuild($method);

        return $this->build($method);
    }
}
