# Tree Data

The aim of this project is to provide a simple api for calculating data about UK native trees.

The provided PHP utilities can be used to get data about a range of tree species. It can work out the CO2 sequestration rate for trees. The data is used as the base data in the Forest Of One Project.

## Installation

Use the package manager [composer](https://getcomposer.org/) to install.

```bash
composer require cowshedworks/trees
```

## Using the Tree Data API

The tree data factory will return a tree data object that can be used to calculate various things about the tree.

```PHP

use CowshedWorks\Trees\TreeDataFactory;

$factory = new TreeDataFactory();
print_r($factory->getTrees());

// prints ['alder', 'birch', 'oak']
```

To instantiate a tree data object you need to pass parameters to the factory, it requires 'circumference' and 'height' in order to build the object. You can also pass it an observed date 'YYYY-MM-DD'. Parameters can be passed to the factory either as an array in the build method or chained before calling build. For example:

```PHP
$treeData = $factory->build('alder', [
  'circumference' => '22in',
  'height'   => '15ft'
]);
```

or 

```PHP
$treeData = $factory->circumference('22in')->height('15ft')->build('alder');
```
It will setup the tree object and calculate some information about it from the circumference and height, it will also work out an approximate age. If an observed date is provided it will attempt to extrapolate the data up to the current date.

```PHP

echo $treeData->getPopularName();
// Alder
print_r($treeData->getCommonNames());
// Array
// (
//     [0] => Alder
//     [1] => Common Alder
//     [2] => Black Alder
//     [3] => European Alder
// )
print_r($treeData->getScientificName());
// Array
// (
//     [0] => Alnus glutinosa
// )
echo $treeData->getEstimatedAge();
// 10 years
echo $treeData->getCarbonWeight();
// 36.3 kg
echo $treeData->getCO2SequestrationPerYear();
// 13.31 kg
echo $treeData->getCO2SequestrationToDate();
// 133.09
```


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
