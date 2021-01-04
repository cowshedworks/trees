<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class CircumferenceFromAgeAndGrowthRate extends StrategyAbstract
{
    public function run(TreeData $treeData): void
    {
        $treeAge = $treeData->getAge();
        $annualAverageCircumferenceGrowthRate = $treeData->getAverageAnnualCircumferenceGrowthRate();

        $treeData->setCircumference(
            $this->unitValueFactory->circumference(
                $treeAge->getValue() * $annualAverageCircumferenceGrowthRate->getValue(),
                'cm'
            )
        );

        $treeData->logBuild('Circumference calculated from growth rate');
    }
}
