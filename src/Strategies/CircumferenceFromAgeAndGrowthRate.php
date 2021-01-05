<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class CircumferenceFromAgeAndGrowthRate extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        $treeAge = $treeData->getAge();
        $annualAverageCircumferenceGrowthRate = $treeData->getAverageAnnualCircumferenceGrowthRate();

        $circumference = $treeAge->getValue() * $annualAverageCircumferenceGrowthRate->getValue();
        if ($circumference > $treeData->getMaxCircumference()) {
            $circumference = $treeData->getMaxCircumference();
        }

        $treeData->setCircumference(
            $this->unitValueFactory->circumference(
                $circumference,
                'cm'
            )
        );

        $treeData->logBuild("Circumference {$circumference} calculated from growth rate");
    }
}
