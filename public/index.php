<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Tree Data Testing Tool</title>
  <meta name="description" content="Tree Data Testing Tool">
  <meta name="author" content="CowshedWorks">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
<div class="container mx-auto p-4">
<?php

require __DIR__.'/../vendor/autoload.php';

use CowshedWorks\Trees\TreeDataFactory;

$factory = new TreeDataFactory();

$species = $_GET['species'] ?? 'alder';
$currentDiameter = $_GET['diameter'] ?? '8in';
$currentCircumference = $_GET['circumference'] ?? '20cm';
$currentHeight = $_GET['height'] ?? '28m';
$currentAge = $_GET['age'] ?? '20years';
$currentObservedDate = $_GET['observed'] ?? '2001-01-01';

$treeParameters = [
    'age'             => $currentAge,
    'diameter'        => $currentDiameter,
    'circumference'   => $currentCircumference,
    'height'          => $currentHeight,
    'observed'        => $currentObservedDate,
];

?>

<h1 class="text-4xl font-black mb-5">Tree Data Testing Tool</h1>

<div class="grid grid-cols-1 gap-4 md:grid-cols-6 bg-gray-50 p-4">
  <div class="md:col-span-2">
    <h4>Parameters</h4>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <select name="species">
            <?php
                foreach ($factory->getTrees() as $tree) {
                    echo "<option value='{$tree}'>{$tree}</option>";
                }
            ?>
        </select>
        <br>
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
        <input type="submit" value="Build Tree Data">
    </form>
  </div>

  <div class="md:col-span-4">
<?php
try {
                $treeData = $factory->{$species}($treeParameters);

                echo '<table class="table-auto text-sm bg-white">';
                echo "<tr class='font-black bg-green-400'><td class='p-2' colspan='2'>Tree Information</td></tr>";
                echo "<tr><td class='p-3'>Tree Name</td><td class='p-3'>{$treeData->getPopularName()}</td></tr>";
                echo "<tr><td class='p-3'>Family</td><td class='p-3'>{$treeData->getFamilyName()}</td></tr>";
                echo '<tr><td class="p-3">Common Name(s)</td><td class="p-3">';
                foreach ($treeData->getCommonNames() as $name) {
                    echo $name.'<br>';
                }
                echo '</td></tr>';
                echo '<tr><td class="p-3">Scientific Name(s)</td><td class="p-3">';
                foreach ($treeData->getScientificName() as $name) {
                    echo $name.'<br>';
                }
                echo '</td></tr>';
                echo "<tr><td class='p-3'>Habitat</td><td class='p-3'>{$treeData->getHabitat()}</td></tr>";
                echo "<tr class='font-black bg-green-400'><td class='p-2' colspan='2'>Tree Attributes</td></tr>";
                echo "<tr><td class='p-3'>Average Max Age</td><td class='p-3'>{$treeData->getMaxAge()}</td></tr>";
                echo "<tr><td class='p-3'>Current Age</td><td class='p-3'>{$treeData->getAge()}</td></tr>";
                echo "<tr><td class='p-3'>Height</td><td class='p-3'>{$treeData->getHeight()}</td></tr>";
                echo "<tr><td class='p-3'>Circumference:</td><td class='p-3'>{$treeData->getCircumference()}</td></tr>";
                echo "<tr><td class='p-3'>Diameter:</td><td class='p-3'>{$treeData->getDiameter()}</td></tr>";
                echo "<tr><td class='p-3'>Weight:</td><td class='p-3'>{$treeData->getWeight()}</td></tr>";
                echo "<tr class='font-black bg-green-400'><td class='p-2' colspan='2'>Growth Rates</td></tr>";
                echo "<tr><td class='p-3'>Actual Average Height Growth Rate:</td><td class='p-3'>{$treeData->getActualAnnualHeightGrowthRate()}</td></tr>";
                echo "<tr><td class='p-3'>Default Average Height Growth Rate:</td><td class='p-3'>{$treeData->getAverageAnnualHeightGrowthRate()}</td></tr>";
                echo "<tr><td class='p-3'>Actual Average Circumference Growth Rate:</td><td class='p-3'>{$treeData->getActualAverageCircumferenceGrowthRate()}</td></tr>";
                echo "<tr><td class='p-3'>Default Average Circumference Growth Rates:</td><td class='p-3'>{$treeData->getAverageAnnualCircumferenceGrowthRate()}</td></tr>";
                echo "<tr class='font-black bg-green-400'><td class='p-2' colspan='2'>Carbon Data</td></tr>";
                echo "<tr><td class='p-3'>Carbon in tree:</td><td class='p-3'>{$treeData->getCarbonWeight()}</td></tr>";
                echo "<tr><td class='p-3'>CO2 Sequestered per year:</td><td class='p-3'>{$treeData->getCO2SequestrationPerYear()}</td></tr>";
                echo "<tr><td class='p-3'>CO2 Sequestered to date:</td><td class='p-3'>{$treeData->getCO2SequestrationToDate()}</td></tr>";
                echo '<tr><td class="p-3">Build Log:</td><td class="p-3">';
                foreach ($treeData->getBuildLog() as $logMessage) {
                    echo ''.$logMessage.'<br>';
                }
                echo '</td></tr>';
                echo '</table>';

                if ($treeData->hasHeightAgeRegressionData()) {
?>
<h4 class="text-xl font-black text-green-600 mt-5">Age to Height Regression Charts</h4>
<script type="text/javascript">
  google.charts.load("current", {packages:["corechart"]});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Height', 'Age'],
      <?php
      foreach ($treeData->getHeightAgeRegressionData()->getHeightX() as $data) {
          echo '['.$data['x'].','.$data['y'].'],';
      } ?>
    ]);

    var options = {
      title: 'Height from Age Regression Data',
      hAxis: {title: 'Height (m)'},
      vAxis: {title: 'Age (years)'},
      crosshair: { trigger: 'both', orientation: 'both' },
      colors: ['#7F9A65'],
      trendlines: {
        0: {
          type: 'polynomial',
          degree: 3,
          visibleInLegend: false,
        }
      }
    };

    var chart = new google.visualization.ScatterChart(document.getElementById('polynomial2_div'));
    chart.draw(data, options);
  }
</script>
<div id='polynomial2_div' style='width: 100%; height: 400px; margin:10px'></div>
<br>

<script type="text/javascript">
     google.charts.load("current", {packages:["corechart"]});
     google.charts.setOnLoadCallback(drawChart);
     function drawChart() {
       var data = google.visualization.arrayToDataTable([
         ['Age', 'Height'],
         <?php
         foreach ($treeData->getHeightAgeRegressionData()->getYearX() as $data) {
             echo '['.$data['x'].','.$data['y'].'],';
         } ?>
       ]);

       var options = {
         title: 'Age from Height Regression Data',
         hAxis: {title: 'Age (years)'},
         vAxis: {title: 'Height (m)'},
         crosshair: { trigger: 'both', orientation: 'both' },
         colors: ['#7F9A65'],
         trendlines: {
           0: {
             type: 'polynomial',
             degree: 4,
             visibleInLegend: false,
           }
         }
       };

       var chart = new google.visualization.ScatterChart(document.getElementById('polynomial3_div'));
       chart.draw(data, options);
     }
   </script>
<?php
                }
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
?>
<div id='polynomial3_div' style='width: 100%; height: 400px; margin:10px'></div>
<br>

    </div>
</div>


</div>
</body>
</html>