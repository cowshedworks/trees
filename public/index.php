<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Tree Data Testing Tool</title>
  <meta name="description" content="Tree Data Testing Tool">
  <meta name="author" content="CowshedWorks">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
      width: 100%;
      font-size: 0.9em;
  }
  table tr td {
      padding: 4px;
      width: 50%;
      vertical-align: top
  }
  </style>
</head>
<body>

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

<h1>Tree Data Testing Tool</h1>

<div class="grid-container">
  <div class="grid-item">
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
  <div class="grid-item">
<?php
try {
                $treeData = $factory->{$species}($treeParameters);

                echo '<table>';
                echo "<tr class='title'><td colspan='2'>Tree Information</td></tr>";
                echo "<tr><td>Tree Name</td><td>{$treeData->getPopularName()}</td></tr>";
                echo "<tr><td>Family</td><td>{$treeData->getFamilyName()}</td></tr>";
                echo '<tr><td>Common Name(s)</td><td>';
                foreach ($treeData->getCommonNames() as $name) {
                    echo $name.'<br>';
                }
                echo '</td></tr>';
                echo '<tr><td>Scientific Name(s)</td><td>';
                foreach ($treeData->getScientificName() as $name) {
                    echo $name.'<br>';
                }
                echo '</td></tr>';
                echo "<tr><td>Habitat</td><td>{$treeData->getHabitat()}</td></tr>";
                echo "<tr class='title'><td colspan='2'>Tree Attributes</td></tr>";
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
                echo '<tr><td>Build Log:</td><td>';
                foreach ($treeData->getBuildLog() as $logMessage) {
                    echo ''.$logMessage.'<br>';
                }
                echo '</td></tr>';
                echo '</table>';

                if ($treeData->hasHeightAgeRegressionData()) {
?>
<h4>Age to Height Regression Charts</h4>
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
<div id='polynomial2_div' style='width: 100%; height: 400px;'></div>
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
<div id='polynomial3_div' style='width: 100%; height: 400px;'></div>
<br>

    </div>
</div>
</body>
</html>