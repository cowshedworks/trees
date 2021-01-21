<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class RecalculateCircumferenceFromAgeAndGrowthRate extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        $treeCircumference = $treeData->getCircumference();
        $averageAnnualCircumferenceGrowthRate = $treeData->getAverageAnnualCircumferenceGrowthRate();

        $yearsCircumferenceGrowthToEstimate = $treeData->getObservationDateDiffYears();
        $growthForEstimate = $yearsCircumferenceGrowthToEstimate * $averageAnnualCircumferenceGrowthRate->getValue();

        $newCircumferenceFromCircumferenceAndEstimate = $treeCircumference->getValue() + $growthForEstimate;

        $maxed = false;
        if ($newCircumferenceFromCircumferenceAndEstimate > $treeData->getMaxCircumference()) {
            $newCircumferenceFromCircumferenceAndEstimate = $treeData->getMaxCircumference();
            $maxed = true;
        }

        $treeData->setCircumference(
            $this->unitValueFactory->circumference(
                $newCircumferenceFromCircumferenceAndEstimate,
                'cm'
            )
        );

        $treeData->logBuild(sprintf(
            'Circumference (%dcm%s) calculated from growth rate',
            round($newCircumferenceFromCircumferenceAndEstimate),
            $maxed ? ' (max) ' : ''
        ));
    }
}
