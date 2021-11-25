<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class AgeFromHeight extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        if ($treeData->hasHeightAgeRegressionData()) {
            bcscale(10);

            $ageFromRegression = $treeData
                ->getHeightAgeRegression()
                ->buildAgeFromHeight()
                ->interpolate($treeData->getHeight()->getValueIn('m'));

            if ($ageFromRegression <= 0) {
                // If the regression returns < 0 for the age, set it to around 3 months,
                // this avoids division by 0 and is perfectly reasonable
                $ageFromRegression = 0.25;
            }

            $treeData->setEstimatedAge(
                $this->unitValueFactory->age(
                    $ageFromRegression,
                    'years'
                )
            );

            $treeData->logBuild('Age calculated from height regression');

            return;
        }

        $treeHeight = $treeData->getHeight();
        $averageAnnualHeightGrowthRate = $treeData->getAverageAnnualHeightGrowthRate();
        $calculatedAge = $treeHeight->getValue() / $averageAnnualHeightGrowthRate->getValue();

        $treeData->setEstimatedAge(
            $this->unitValueFactory->age(
                $calculatedAge,
                'years'
            )
        );

        $treeData->logBuild(
            sprintf(
                'Age (%d years) calculated from height',
                round($calculatedAge, 2)
            )
        );
    }
}
