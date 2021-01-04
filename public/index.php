<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Tree Data Testing Tool</title>
  <meta name="description" content="Tree Data Testing Tool">
  <meta name="author" content="CowshedWorks">
  <style>
  body {
      font-family: sans-serif;
      color: black;
      padding:30px;
  }
  .grid-container {
      display: grid;
      grid-template-columns: auto auto;
      background-color: #7F9A65;
      padding: 10px;
   }
   .grid-item {
      background-color: rgba(255, 255, 255, 0.8);
      border: 1px solid rgba(0, 0, 0, 0.8);
      margin:1px;
      padding: 20px;
   }
  .title {
      background-color: white;
      font-weight: bold;
      font-size: 1.2em;
  }
  table {
      font-size: 0.9em;
  }
  table tr td {
      padding: 4px;
  }
  </style>
</head>
<body>

<?php
$currentDiameter = $_GET['diameter'] ?? '8in';
$currentCircumference = $_GET['circumference'] ?? '20cm';
$currentHeight = $_GET['height'] ?? '28m';
$currentAge = $_GET['age'] ?? '20years';
$currentObservedDate = $_GET['observed'] ?? '2001-01-01';
?>

<h1>Tree Data Testing Tool</h1>

<div class="grid-container">
  <div class="grid-item">
    <h4>Parameters</h4>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="text" name="height" placeholder="height" value="<?php echo $currentHeight; ?>"">
    <br>
    <input type="text" name="diameter" placeholder="diameter" value="<?php echo $currentDiameter; ?>"">
    <br>
    <input type="text" name="circumference" placeholder="circumference" value="<?php echo $currentCircumference; ?>"">
    <br>
    <input type="text" name="age" placeholder="age" value="<?php echo $currentAge; ?>"">
    <br>
    <input type="text" name="observed" placeholder="observed date 1990-01-04" value="<?php echo $currentObservedDate; ?>"">
    <hr>
    <input type="submit">
    </form>
  
  </div>
  <div class="grid-item">
<?php

require __DIR__.'/../vendor/autoload.php';

use CowshedWorks\Trees\TreeDataFactory;
$factory = new TreeDataFactory();

$parameters = [
    'age'      => $currentAge,
    'diameter'   => $currentDiameter,
    'circumference'   => $currentCircumference,
    'height'   => $currentHeight,
    'observed' => $currentObservedDate,
];

$treeData = $factory->alder($parameters);

echo '<table>';
echo "<tr class='title'><td colspan='2'>Tree Attributes</td></tr>";
echo "<tr><td>Tree Name</td><td>{$treeData->getPopularName()}</td></tr>";
echo "<tr><td>Average Max Age</td><td>{$treeData->getMaxAge()}</td></tr>";
echo "<tr><td>Current Age</td><td>{$treeData->getAge()}</td></tr>";
echo "<tr><td>Height</td><td>{$treeData->getHeight()}</td></tr>";
echo "<tr><td>Circumference:</td><td>{$treeData->getCircumference()}</td></tr>";
echo "<tr><td>Diameter:</td><td>{$treeData->getDiameter()}</td></tr>";
echo "<tr><td>Weight:</td><td>{$treeData->getWeight()}</td></tr>";
echo "<tr class='title'><td colspan='2'>Growth Rates</td></tr>";
echo "<tr><td>Actual Average Height Growth Rate:</td><td>{$treeData->getActualAnnualHeightGrowthRate()}</td></tr>";
echo "<tr><td>Default Average Height Growth Rate:</td><td>{$treeData->getAverageAnnualHeightGrowthRate()}</td></tr>";
echo "<tr><td>Actual Average Circumference Growth Rate:</td><td>{$treeData->getActualAverageCircumferenceGrowthRate()}</td></tr>";
echo "<tr><td>Default Average Circumference Growth Rates:</td><td>{$treeData->getAverageAnnualCircumferenceGrowthRate()}</td></tr>";
echo "<tr class='title'><td colspan='2'>Carbon Data</td></tr>";
echo "<tr><td>Carbon in tree:</td><td>{$treeData->getCarbonWeight()}</td></tr>";
echo "<tr><td>CO2 Sequestered per year:</td><td>{$treeData->getCO2SequestrationPerYear()}</td></tr>";
echo "<tr><td>CO2 Sequestered to date:</td><td>{$treeData->getCO2SequestrationToDate()}</td></tr>";
echo "</table>";

?>
    </div>
</div>
</body>
</html>