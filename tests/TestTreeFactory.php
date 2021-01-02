<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\ConfigLoader;
use CowshedWorks\Trees\TreeDataFactory;

trait TestTreeFactory
{
    protected function getTreeDataFactory(): TreeDataFactory
    {
        $configLoader = new ConfigLoader();
        $configLoader->setDataDir(__DIR__.'/data');

        return new TreeDataFactory($configLoader);
    }
}
