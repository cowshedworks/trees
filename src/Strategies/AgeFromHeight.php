<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class AgeFromHeight extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        $treeHeight = $treeData->getHeight();
        $averageAnnualHeightGrowthRate = $treeData->getAverageAnnualHeightGrowthRate();
        $calculatedAge = $treeHeight->getValue() / $averageAnnualHeightGrowthRate->getValue();

        $treeData->setAge(
            $this->unitValueFactory->age(
                $calculatedAge,
                'years'
            )
        );

        $treeData->logBuild(
            sprintf(
                'Age (%d years) calculated from height',
                round($calculatedAge, 2)
            )
        );
    }
}
