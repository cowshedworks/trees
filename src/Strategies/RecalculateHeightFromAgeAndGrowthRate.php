<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class RecalculateHeightFromAgeAndGrowthRate extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        $treeHeight = $treeData->getHeight();
        $averageAnnualHeightGrowthRate = $treeData->getAverageAnnualHeightGrowthRate();

        $yearsHeightGrowthToEstimate = $treeData->getObservationDateDiffYears();
        $growthForEstimate = $yearsHeightGrowthToEstimate * $averageAnnualHeightGrowthRate->getValue();

        $newHeightFromHeightAndEstimate = $treeHeight->getValue() + $growthForEstimate;

        $maxed = false;
        if ($newHeightFromHeightAndEstimate > $treeData->getMaxHeight()) {
            $newHeightFromHeightAndEstimate = $treeData->getMaxHeight();
            $maxed = true;
        }

        $treeData->setHeight(
            $this->unitValueFactory->height(
                $newHeightFromHeightAndEstimate,
                'cm'
            )
        );

        $treeData->logBuild(sprintf(
            'Height (%dcm%s) calculated from growth rate',
            round($newHeightFromHeightAndEstimate),
            $maxed ? ' (max) ' : ''
        ));
    }
}
