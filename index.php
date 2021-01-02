<?php

require __DIR__.'/vendor/autoload.php';

use CowshedWorks\Trees\TreeDataFactory;

$factory = new TreeDataFactory();

$treeData = $factory->alder([
    'age'      => '60years',
    'diameter' => '8in',
    'height'   => '15ft',
]);

echo $treeData->getPopularName();
echo PHP_EOL;
echo 'Average Max Age: '.$treeData->getMaxAge();
echo PHP_EOL;
echo 'Current Age: '.$treeData->describeAge();
echo PHP_EOL;
echo 'Height of tree: '.$treeData->describeHeight();
echo PHP_EOL;
echo 'Circumference of tree: '.$treeData->describeCircumference();
echo PHP_EOL;
echo 'Diameter of tree: '.$treeData->describeDiameter();
echo PHP_EOL;
echo 'Weight of tree: '.$treeData->describeWeight();
echo PHP_EOL;
echo PHP_EOL;
echo '##### GROWTH RATES #####';
echo PHP_EOL;
echo 'Actual Average Height Growth Rate: '.$treeData->describeActualHeightGrowthRate();
echo PHP_EOL;
echo 'Default Average Height Growth Rate: '.$treeData->describeAverageHeightGrowthRate();
echo PHP_EOL;
echo 'Actual Average Circumference Growth Rate: '.$treeData->describeActualCircumferenceGrowthRate();
echo PHP_EOL;
echo 'Default Average Circumference Growth Rates: '.$treeData->describeAverageCircumferenceGrowthRate();
echo PHP_EOL;
echo PHP_EOL;
echo '##### CARBON / CO2 #####';
echo PHP_EOL;
echo 'Carbon in tree: '.$treeData->describeCarbonWeight();
echo PHP_EOL;
echo 'CO2 Sequestered per year: '.$treeData->describeCO2SequestrationPerYear();
echo PHP_EOL;
echo 'CO2 Sequestered to date: '.$treeData->describeCO2SequestrationToDate();
echo PHP_EOL;
