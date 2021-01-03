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
        $treeHeightRegesssionData = $treeData->getHeightRegression();
        $averageAnnualHeightGrowthRate = $treeData->getAverageAnnualHeightGrowthRate();
        $regression = new PolynomialRegression(4);
        foreach ($treeHeightRegesssionData as $regressionData) {
            $regression->addData($regressionData['year'], $regressionData['value']['value']);
        }
        $coefficients = $regression->getCoefficients();
        $ageFromRegression = $regression->interpolate($coefficients, $treeHeight->getValueIn('m'));

        $treeData->setAge(
            $this->unitValueFactory->age(
                $ageFromRegression,
                'years'
            )
        );
    }
}
