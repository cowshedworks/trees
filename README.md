# Trees

Tree data for UK native trees

The provided PHP utilities can be used to work out the CO2 sequestration rate for trees. The data is used as the base data in the Plant a Tree Project.

### Currently work in progress, auto calculations aren't correct

## Installation

### NOT YET AVAILABLE ON PACKAGIST

Use the package manager [composer](https://getcomposer.org/) to install.

```bash
composer require cowshedworks/trees
```

## Using the Tree Data API

The tree data factory will return a tree data object that can be used to calculate various things about the tree.

Call getTrees() on TreeDataFactory to list the available tree data objects.

```PHP

use CowshedWorks\Trees\TreeDataFactory;

$factory = new TreeDataFactory();
print_r($factory->getTrees());

// prints ['alder', 'birch', 'oak']
```

To instantiate a data object you need to pass parameters to the constructor, it will require one of 'circumference', 'age', or 'height' in order to build the object.

If any of 'circumference', 'age', or 'height' are not provided when building the object it will attempt to guess them using values from the species data. These values are needed for the CO2 sequestration calculations, the more accurate the data you provide when building the object the more accurate the calculations will be. That said, these are general calculations intended for approximations, there are lots of factors that will affect these values in the real world.

```PHP
$treeData = $factory->alder([
    'age'      => '10years',
    'diameter' => '8in',
    'height'   => '15feet'
]);

$treeData->getPopularName();
// Alder
$treeData->getCommonNames();
// Array
// (
//     [0] => Alder
//     [1] => Common Alder
//     [2] => Black Alder
//     [3] => European Alder
// )
$treeData->getScientificName();
// Array
// (
//     [0] => Alnus glutinosa
// )
$treeData->describeAge();
// 10 years
$treeData->describeCarbonWeight();
// 47.15 kg
$treeData->describeCO2SequestrationPerYear();
// 17.29 kg
$treeData->describeCO2SequestrationToDate();
// 172.87 kg
```


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
