<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\SpeciesDataLoader;
use CowshedWorks\Trees\TreeDataFactory;

trait TestTreeFactory
{
    protected function getTreeDataFactory(): TreeDataFactory
    {
        $loader = new SpeciesDataLoader();
        $loader->setDataDir(__DIR__.'/data');

        return new TreeDataFactory($loader);
    }

    protected function getTreeDataFactoryWithEmptyData(): TreeDataFactory
    {
        $loader = new SpeciesDataLoader();
        $loader->setDataDir(__DIR__.'/data/empty');

        return new TreeDataFactory($loader);
    }
}
