<?php

namespace CowshedWorks\Trees;

class TreeCalculatorFactory
{
    const DATADIR = __DIR__ .'/data';

    protected bool $configFilesLoaded = false;

    protected array $availableTrees = [];

    public function __construct()
    {
        $this->loadConfig();
    }

    public function getTrees(): array
    {
        return $this->availableTrees;
    }

    private function loadConfig(): void
    {
        $dataFiles = array_diff(scandir(self::DATADIR), array('..', '.'));
        $this->availableTrees = array_values(array_map(function($filename){
            return str_replace('.json', '', $filename);
        },
        $dataFiles));
    }
}
