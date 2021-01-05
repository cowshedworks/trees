<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class DiameterFromCircumference extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        $treeCircumference = $treeData->getCircumference();

        $diameter = $treeCircumference->getValue() / M_PI;
        if ($diameter > $treeData->getMaxDiameter()) {
            $diameter = $treeData->getMaxDiameter();
        }

        $treeData->setDiameter(
            $this->unitValueFactory->diameter(
                $diameter,
                'cm'
            )
        );

        $treeData->logBuild("Diameter {$diameter} calculated from circumference");
    }
}
