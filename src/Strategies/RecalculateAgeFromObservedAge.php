<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class RecalculateAgeFromObservedAge extends StrategyAbstract
{
    public function execute(TreeData $treeData): void
    {
        $treeAge = $treeData->getAge();

        $calculatedAge = $treeAge->getValue() + $treeData->getObservationDateDiffYears();

        $treeData->setAge(
            $this->unitValueFactory->age(
                $calculatedAge,
                'years'
            )
        );

        $treeData->logBuild(
            sprintf(
                'Age (%d years) recalculated from observed age',
                round($calculatedAge, 2)
            )
        );
    }
}
