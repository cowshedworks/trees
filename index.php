<?php

require __DIR__.'/vendor/autoload.php';

use CowshedWorks\Trees\TreeDataFactory;

$factory = new TreeDataFactory();

$treeData = $factory->alder([
    // 'age'      => '60years',
    // 'diameter' => '8in',
    'height'   => '28m',
    'observed' => '1890-01-01',
]);

echo $treeData->getPopularName();
echo PHP_EOL;
echo 'Average Max Age: '.$treeData->getMaxAge();
echo PHP_EOL;
echo 'Current Age: '.$treeData->getAge();
echo PHP_EOL;
echo 'Height of tree: '.$treeData->getHeight();
echo PHP_EOL;
echo 'Circumference of tree: '.$treeData->getCircumference();
echo PHP_EOL;
echo 'Diameter of tree: '.$treeData->getDiameter();
echo PHP_EOL;
echo 'Weight of tree: '.$treeData->getWeight();
echo PHP_EOL;
echo PHP_EOL;
echo '##### GROWTH RATES #####';
echo PHP_EOL;
echo 'Actual Average Height Growth Rate: '.$treeData->getActualAnnualHeightGrowthRate();
echo PHP_EOL;
echo 'Default Average Height Growth Rate: '.$treeData->getAverageAnnualHeightGrowthRate();
echo PHP_EOL;
echo 'Actual Average Circumference Growth Rate: '.$treeData->getActualAverageCircumferenceGrowthRate();
echo PHP_EOL;
echo 'Default Average Circumference Growth Rates: '.$treeData->getAverageAnnualCircumferenceGrowthRate();
echo PHP_EOL;
echo PHP_EOL;
echo '##### CARBON / CO2 #####';
echo PHP_EOL;
echo 'Carbon in tree: '.$treeData->getCarbonWeight();
echo PHP_EOL;
echo 'CO2 Sequestered per year: '.$treeData->getCO2SequestrationPerYear();
echo PHP_EOL;
echo 'CO2 Sequestered to date: '.$treeData->getCO2SequestrationToDate();
echo PHP_EOL;
