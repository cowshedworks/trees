<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class AgeFromCircumference extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        $circumference = $treeData->getCircumference();
        $averageAnnualCircumferenceGrowthRate = $treeData->getAverageAnnualCircumferenceGrowthRate();

        $treeData->setEstimatedAge(
            $this->unitValueFactory->age(
                $circumference->getValue() / $averageAnnualCircumferenceGrowthRate->getValue(),
                'years'
            )
        );

        $treeData->logBuild('Age calculated from circumference');
    }
}
