<?php

require __DIR__ . '/vendor/autoload.php';

use CowshedWorks\Trees\TreeDataFactory;

$factory = new TreeDataFactory();

$treeData = $factory->alder([
    'age'      => '10years',
    'diameter' => '8in',
    'height'   => '15feet',
]);

echo $treeData->getPopularName();
echo PHP_EOL;
print_r($treeData->getCommonNames());
echo PHP_EOL;
print_r($treeData->getScientificName());
echo PHP_EOL;
echo $treeData->describeAge();
echo PHP_EOL;
echo 'Weight of tree: ' . $treeData->describeWeight();
echo PHP_EOL;
echo 'Carbon in tree: ' . $treeData->describeCarbonWeight();
echo PHP_EOL;
echo 'CO2 Sequestered per year: ' . $treeData->describeCO2SequestrationPerYear();
echo PHP_EOL;
echo 'CO2 Sequestered to date: ' . $treeData->describeCO2SequestrationToDate();
echo PHP_EOL;