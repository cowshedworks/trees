<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;
use DrQue\PolynomialRegression;

class AgeFromHeightRegression extends StrategyAbstract
{
    public function run(TreeData $treeData): void
    {
        bcscale(10);

        $treeHeight = $treeData->getHeight();
        $treeHeightRegresssionData = $treeData->getHeightRegression();

        // 4th order Polynomial describes the relationship with an
        // r squared of 0.99251952888989
        $regression = new PolynomialRegression(4);

        foreach ($treeHeightRegresssionData as $regressionData) {
            $regression->addData($regressionData['value']['value'], $regressionData['year']);
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
    }
}
