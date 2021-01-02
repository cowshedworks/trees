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
echo 'Current Age: '.$treeData->getAge()->describe();
echo PHP_EOL;
echo 'Height of tree: '.$treeData->getHeight()->describe();
echo PHP_EOL;
echo 'Circumference of tree: '.$treeData->getCircumference()->describe();
echo PHP_EOL;
echo 'Diameter of tree: '.$treeData->getDiameter()->describe();
echo PHP_EOL;
echo 'Weight of tree: '.$treeData->getWeight()->describe();
echo PHP_EOL;
echo PHP_EOL;
echo '##### GROWTH RATES #####';
echo PHP_EOL;
echo 'Actual Average Height Growth Rate: '.$treeData->getActualHeightGrowthRate()->describe();
echo PHP_EOL;
echo 'Default Average Height Growth Rate: '.$treeData->getAverageAnnualHeightGrowthRate()->describe();
echo PHP_EOL;
echo 'Actual Average Circumference Growth Rate: '.$treeData->getActualCircumferenceGrowthRate()->describe();
echo PHP_EOL;
echo 'Default Average Circumference Growth Rates: '.$treeData->getAverageAnnualCircumferenceGrowthRate()->describe();
echo PHP_EOL;
echo PHP_EOL;
echo '##### CARBON / CO2 #####';
echo PHP_EOL;
echo 'Carbon in tree: '.$treeData->getCarbonWeight()->describe();
echo PHP_EOL;
echo 'CO2 Sequestered per year: '.$treeData->getCO2SequestrationPerYear()->describe();
echo PHP_EOL;
echo 'CO2 Sequestered to date: '.$treeData->getCO2SequestrationToDate()->describe();
echo PHP_EOL;
