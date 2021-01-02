# Tree Data

Tree data for UK native trees

The provided PHP utilities can be used to get data about a range of tree species. It can work out the CO2 sequestration rate for trees. The data is used as the base data in the Plant a Tree Project.

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

If any of 'circumference', 'age', or 'height' are not provided when building the object it will attempt to guess them using values from the species data. These values are needed for the CO2 sequestration calculations, the more accurate the data you provide when building the object the more accurate the calculations will be.

That said, these are general calculations intended for approximations, there are lots of factors that will affect these values in the real world.

```PHP
$treeData = $factory->alder([
    'age'      => '10years',
    'diameter' => '8in',
    'height'   => '15ft'
]);

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
echo $treeData->getAge();
// 10 years
echo $treeData->getCarbonWeight();
// 47.15 kg
echo $treeData->getCO2SequestrationPerYear();
// 17.29 kg
echo $treeData->getCO2SequestrationToDate();
// 172.87 kg
```


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
