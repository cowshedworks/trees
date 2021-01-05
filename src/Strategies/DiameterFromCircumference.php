<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class DiameterFromCircumference extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        $treeCircumference = $treeData->getCircumference();

        $diameter = $treeCircumference->getValue() / M_PI;
        $maxed = false;
        if ($diameter > $treeData->getMaxDiameter()) {
            $diameter = $treeData->getMaxDiameter();
            $maxed = true;
        }

        $treeData->setDiameter(
            $this->unitValueFactory->diameter(
                $diameter,
                'cm'
            )
        );

        $treeData->logBuild(sprintf(
            'Diameter %dcm%s calculated from circumference',
            round($diameter),
            $maxed ? ' (max) ' : ''
        ));
    }
}
