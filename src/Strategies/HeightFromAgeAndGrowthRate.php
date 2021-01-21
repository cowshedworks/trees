<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class HeightFromAgeAndGrowthRate extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        $treeAge = $treeData->getAge();
        $averageAnnualHeightGrowthRate = $treeData->getAverageAnnualHeightGrowthRate();
        $heightFromGrowthRate = $treeAge->getValue() * $averageAnnualHeightGrowthRate->getValue();

        $maxed = false;
        if ($heightFromGrowthRate > $treeData->getMaxHeight()) {
            $heightFromGrowthRate = $treeData->getMaxHeight();
            $maxed = true;
        }

        $treeData->setHeight(
            $this->unitValueFactory->height(
                $heightFromGrowthRate,
                'cm'
            )
        );

        $treeData->logBuild(sprintf(
            'Height (%dcm%s) calculated from growth rate',
            round($heightFromGrowthRate),
            $maxed ? ' (max) ' : ''
        ));
    }
}
