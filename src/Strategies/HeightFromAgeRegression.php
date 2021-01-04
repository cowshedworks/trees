<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;
use DrQue\PolynomialRegression;

class HeightFromAgeRegression extends StrategyAbstract
{
    public function run(TreeData $treeData): void
    {
        bcscale(10);

        $treeAge = $treeData->getAge();
        $treeHeightRegresssionData = $treeData->getHeightRegression();

        // 4th order Polynomial describes the relationship with an
        // r squared of 0.99251952888989
        $regression = new PolynomialRegression(3);

        foreach ($treeHeightRegresssionData as $regressionData) {
            $regression->addData($regressionData['year'], $regressionData['value']['value']);
        }

        $heightFromRegression = $regression->interpolate(
            $regression->getCoefficients(),
            $treeAge->getValue()
        );

        $treeData->setHeight(
            $this->unitValueFactory->height(
                $heightFromRegression * 100,
                'cm'
            )
        );

        $treeData->logBuild('Height calculated from age regression');
    }
}
