<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class AgeFromHeight extends StrategyAbstract
{
    public function run(TreeData $treeData): void
    {
        $treeHeight = $treeData->getHeight();
        $averageAnnualHeightGrowthRate = $treeData->getAverageAnnualHeightGrowthRate();

        $treeData->setAge(
            $this->unitValueFactory->age(
                $treeHeight->getValue() / $averageAnnualHeightGrowthRate->getValue(),
                'years'
            )
        );
    }
}
