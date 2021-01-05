<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class HeightFromAge extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        if ($treeData->hasHeightAgeRegressionData()) {
            bcscale(10);

            $heightFromRegression = $treeData
                ->getHeightAgeRegression()
                ->buildHeightFromAge()
                ->interpolate($treeData->getAge()->getValue());

            $maxed = false;
            if ($heightFromRegression <= 0 || $heightFromRegression > $treeData->getMaxHeight()) {
                $heightFromRegression = $treeData->getMaxHeight();
                $maxed = true;
            }

            $treeData->setHeight(
                $this->unitValueFactory->height(
                    $heightFromRegression,
                    'cm'
                )
            );

            $treeData->logBuild(sprintf(
                'Height %dcm%s calculated from age regression',
                round($heightFromRegression),
                $maxed ? ' (max) ' : ''
            ));

            return;
        }

        $treeAge = $treeData->getAge();
        $averageAnnualHeightGrowthRate = $treeData->getAverageAnnualHeightGrowthRate();
        $heightFromGrowthRate = $treeAge->getValue() * $averageAnnualHeightGrowthRate->getValue();

        $maxed = false;
        if ($heightFromGrowthRate > $treeData->getMaxHeight()) {
            $heightFromGrowthRate = $treeData->getMaxHeight();
            $maxed = true;
        }

        $treeData->setHeight(
            $this->unitValueFactory->height(
                $heightFromGrowthRate,
                'cm'
            )
        );

        $treeData->logBuild(sprintf(
            'Height %dcm%s calculated from growth rate',
            round($heightFromGrowthRate),
            $maxed ? ' (max) ' : ''
        ));
    }
}
