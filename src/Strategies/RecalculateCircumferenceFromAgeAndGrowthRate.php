<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class RecalculateCircumferenceFromAgeAndGrowthRate extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        $treeAge = $treeData->getAge();
        $annualAverageCircumferenceGrowthRate = $treeData->getAverageAnnualCircumferenceGrowthRate();

        $circumference = $treeAge->getValue() * $annualAverageCircumferenceGrowthRate->getValue();
        $maxed = false;
        if ($circumference > $treeData->getMaxCircumference()) {
            $circumference = $treeData->getMaxCircumference();
            $maxed = true;
        }

        $treeData->setCircumference(
            $this->unitValueFactory->circumference(
                $circumference,
                'cm'
            )
        );

        $treeData->logBuild(sprintf(
            'Circumference (%dcm%s) calculated from growth rate',
            round($circumference),
            $maxed ? ' (max) ' : ''
        ));
    }
}
