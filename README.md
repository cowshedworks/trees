# Trees
Tree data for UK native trees

The provided PHP utilities can be used to work out the CO2 sequestration rate for trees. The data is used as the base data in the Plant a Tree Project.

Please feel free to use and submit modifications/improvements to the data.

### Aging a tree
Divide the circumference by the growth rate coefficients (cm per year) to get an approximate age in years

Default value is 2.5 cm/year

- Holly: 1.25
- Yew: 1.25
- Oak: 1.88
- Ash: 2.5
- Beech: 2.5
- Elm: 2.5
- Hazel: 2.5
- Sycamore: 2.75
- Pine: 3.13
- Spruce: 3.13

eg 100cm Oak tree = 100 / 1.88 = 53 years old


### CO2 Sequestration

Source for calculations
*Carbon sequestration: how much can forestry sequester CO2?, Forestry Research and Engineering: International Journal*

Process for working out C02 sequestration for a tree is:

Inputs needed
    - tree species (must be one of the available types)
    - trunk circumference

- Calculate the age of the tree
- Calculate the height of the tree from the age and height growth rate
- Calculate total green weight
- Calculate the total dry weight
- Calculate the carbon weight
- Calculate the carbon sequestered by the tree, total and per year

#### TOTAL GREEN WEIGHT
To get the CO2 sequestration rate of the tree we need to work out the total tree weight, this is the above ground plus the below ground weight. The below ground weight is an average value of 20% of the above ground weight.

D = tree diameter (inches)
H = tree height (feet)
COEFFICIENT = IF (D > 11) 0.15 ELSE 0.25

Above ground weight (AGW) = COEEFICIENT (D*D) H
Below ground weight (BGW) = AGW * 0.2
Total green weight (TGW) = BGW + AGW

#### TOTAL DRY WEIGHT
Assumption is that 72.5% of tree weight is dry matter, 27.5% is mosture.

Total dry weight (TDW) = TGW * 0.725

#### CARBON WEIGHT
The carbon content is roughly 50% of the dry weight of the tree.

Carbon Weight (TCW) = TDW * 0.5

#### TOTAL CARBON SEQUESTERED
To calculate the amount of CO2 that the tree has sequestered from the atmosphere you need to multiply the carbon weight by the CO2 sequestration coefficient. This is the ratio of the atomic numbers of carbon dioxide to carbon, which is 43.999915 / 12.001115 which is 3.6663

CO2 SEQUESTRATION COEFFICIENT = 3.6663

Total carbon sequestered (TCS) = TCW * CO2 SEQUESTRATION COEFFICIENT

##### Tree example:
10 years old
15 feet tall HEIGHT
8 inch trunk DIAMETER

AGW = (0.25)(DIAMETER*DIAMETER)(HEIGHT) = 0.25(82)(15) = 240 lbs
TGW = 1.2 * AGW = 1.2 * 240 = 288 lbs
TDW = 0.725 * TGW = 0.725 * 288 = 208.8 lbs
TCW = 0.5 * TDW  = 0.5 * 208.8 = 104.4 lbs
TCS = 3.67 * TCW  = 3.67 * 104.4 

= 382.8 lbs CO2 sequestered in 10 years


### Tree Calculations API

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
