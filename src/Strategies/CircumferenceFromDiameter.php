<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class CircumferenceFromDiameter extends StrategyAbstract
{
    public function run(TreeData $treeData): void
    {
        $treeDiameter = $treeData->getDiameter();

        $treeData->setCircumference(
            $this->unitValueFactory->circumference(
                $treeDiameter->getValue() * M_PI,
                'cm'
            )
        );

        $treeData->logBuild('Circumference calculated from diameter');
    }
}
