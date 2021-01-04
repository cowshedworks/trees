<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class AgeFromDiameter extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        $diameter = $treeData->getDiameter();
        $averageAnnualCircumferenceGrowthRate = $treeData->getAverageAnnualCircumferenceGrowthRate();
        $circumference = ($diameter->getValue() / 2) * (M_PI * 2);

        $treeData->setAge(
            $this->unitValueFactory->age(
                $circumference / $averageAnnualCircumferenceGrowthRate->getValue(),
                'years'
            )
        );

        $treeData->logBuild('Age calculated from diameter');
    }
}
