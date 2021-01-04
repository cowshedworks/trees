<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;
use DrQue\PolynomialRegression;

class HeightFromAge extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        if ($treeData->hasHeightAgeRegressionData()) {
            bcscale(10);

            $treeAge = $treeData->getAge();
            $treeHeightRegresssionData = $treeData->getHeightAgeRegressionData();
            $regression = new PolynomialRegression(3);

            foreach ($treeHeightRegresssionData as $regressionData) {
                $regression->addData($regressionData['year'], $regressionData['value']['value']);
            }

            $heightFromRegression = ($regression->interpolate(
                $regression->getCoefficients(),
                $treeAge->getValue()
            ) * 100);

            if ($heightFromRegression <= 0) {
                $heightFromRegression = $treeData->getMaxHeight();
            }

            $treeData->setHeight(
                $this->unitValueFactory->height(
                    $heightFromRegression,
                    'cm'
                )
            );

            $treeData->logBuild('Height calculated from age regression');

            return;
        }

        $treeAge = $treeData->getAge();
        $averageAnnualHeightGrowthRate = $treeData->getAverageAnnualHeightGrowthRate();

        $treeData->setHeight(
            $this->unitValueFactory->height(
                $treeAge->getValue() * $averageAnnualHeightGrowthRate->getValue(),
                'cm'
            )
        );

        $treeData->logBuild('Height calculated from growth rate');
    }
}
