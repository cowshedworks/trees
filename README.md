# Trees
Tree data for UK native trees

The provided PHP utilities can be used to work out the CO2 sequestration rate for trees. The data is used as the base data in the Plant a Tree Project.

Please feel free to use and submit modifications/improvements to the data.

### Tree Data API

The tree data factory will return a tree data object that can be used to calculate various things about the tree.

Call getTrees() on TreeDataFactory to list the available tree data objects.

To instantiate a data object you need to pass parameters to the contructor, it will require one of 'circumference', 'age', or 'height' in order to build the calculator.

```PHP

use CowshedWorks\Trees\TreeDataFactory;

$factory = new TreeDataFactory();
echo $factory->getTrees();

// prints ['alder', 'birch', 'oak']

$treeData = $factory->alder(['circumference' = '33cm']);

$treeData->getName();

$treeData->getAge();

$treeData->getMaxAge();

$treeData->getCO2SequestrationPerYear();

$treeData->getCO2SequestrationToDate();

$treeData->getCO2SequestrationLifetime();
```
