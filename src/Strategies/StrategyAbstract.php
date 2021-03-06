<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;
use CowshedWorks\Trees\UnitValues\UnitValueFactory;

abstract class StrategyAbstract
{
    protected UnitValueFactory $unitValueFactory;

    public function __construct()
    {
        $this->unitValueFactory = new UnitValueFactory();
    }

    abstract public function execute(TreeData $treeData): void;
}
