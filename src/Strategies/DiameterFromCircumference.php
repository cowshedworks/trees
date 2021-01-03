<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;
use CowshedWorks\Trees\UnitValueFactory;

class DiameterFromCircumference extends StrategyAbstract
{
    public function run(TreeData $treeData): void
    {
        $treeCircumference = $treeData->getCircumference();
        
        $treeData->setDiameter(
            $this->unitValueFactory->diameter(
                $treeCircumference->getValue() / M_PI,
                'cm'
            )
        );
    }
}
