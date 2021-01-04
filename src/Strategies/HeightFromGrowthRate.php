<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class HeightFromGrowthRate extends StrategyAbstract
{
    public function run(TreeData $treeData): void
    {
        $treeAge = $treeData->getAge();
        $averageAnnualHeightGrowthRate = $treeData->getAverageAnnualHeightGrowthRate();

        $treeData->setHeight(
            $this->unitValueFactory->height(
                $treeAge->getValue() * $averageAnnualHeightGrowthRate->getValue(),
                'cm'
            )
        );

        $treeData->logBuild('Height calculated from growth rate');
    }
}
