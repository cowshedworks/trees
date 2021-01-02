<?php

require __DIR__.'/vendor/autoload.php';

use CowshedWorks\Trees\TreeDataFactory;

$factory = new TreeDataFactory();

$treeData = $factory->alder([
    'age'      => '60years',
    // 'diameter' => '8in',
    // 'height'   => '15feet',
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
echo 'Weight of tree: '.$treeData->describeWeight();
echo PHP_EOL;
// echo 'Carbon in tree: '.$treeData->describeCarbonWeight();
// echo PHP_EOL;
// echo 'CO2 Sequestered per year: '.$treeData->describeCO2SequestrationPerYear();
// echo PHP_EOL;
// echo 'CO2 Sequestered to date: '.$treeData->describeCO2SequestrationToDate();
// echo PHP_EOL;
