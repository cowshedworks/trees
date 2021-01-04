<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;
use DrQue\PolynomialRegression;

class AgeFromHeight extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        if ($treeData->hasHeightAgeRegressionData()) {
            bcscale(10);

            $treeHeight = $treeData->getHeight();
            $treeHeightRegresssionData = $treeData->getHeightAgeRegressionData()->getHeightX();

            $regression = new PolynomialRegression(4);

            foreach ($treeHeightRegresssionData as $regressionData) {
                $regression->addData($regressionData['x'], $regressionData['y']);
            }

            $ageFromRegression = $regression->interpolate(
                $regression->getCoefficients(),
                $treeHeight->getValueIn('m')
            );

            if ($ageFromRegression <= 0) {
                // If the regression returns < 0 for the age, set it to around 3 months,
                // this avoids division by 0 and is perfectly reasonable
                $ageFromRegression = 0.25;
            }

            $treeData->setAge(
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

        $treeData->setAge(
            $this->unitValueFactory->age(
                $treeHeight->getValue() / $averageAnnualHeightGrowthRate->getValue(),
                'years'
            )
        );

        $treeData->logBuild('Age calculated from height');
    }
}
