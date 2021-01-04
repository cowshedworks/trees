<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Strategies;

use CowshedWorks\Trees\TreeData;

class RecalculateAgeFromObservedAge extends StrategyAbstract
{
    public function run(TreeData $treeData): void
    {
        $treeAge = $treeData->getAge();

        $treeData->setAge(
            $this->unitValueFactory->age(
                $treeAge->getValue() + $treeData->getObservationDateDiffYears(),
                'years'
            )
        );

        $treeData->logBuild('Age recalculated from observed age');
    }
}
