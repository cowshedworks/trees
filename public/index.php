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
<body class="bg-green-800">
<div class="container mx-auto p-4">
<?php

require __DIR__.'/../vendor/autoload.php';

use CowshedWorks\Trees\TreeDataFactory;

$factory = new TreeDataFactory();

$species = $_GET['species'] ?? 'alder';
$currentCircumference = $_GET['circumference'] ?? '170cm';
$currentHeight = $_GET['height'] ?? '28m';
$currentAge = $_GET['age'] ?? null;
$currentObservedDate = $_GET['observed'] ?? '2001-01-01';

$treeParameters = [
    'age'             => $currentAge,
    'circumference'   => $currentCircumference,
    'height'          => $currentHeight,
    'observed'        => $currentObservedDate,
];

?>

<h1 class="text-4xl font-black text-white text-center mb-5 text-shadow-lg">Tree Data Testing Tool</h1>

<div class="grid grid-cols-1 gap-4 lg:grid-cols-6 p-4">

  <div class="lg:col-span-2">
    <div class="flex items-start h-screen w-full bg-green-lighter">
      <div class="w-full bg-white rounded shadow-lg p-8">
        <h4 class="text-xl font-black text-green-600 mb-5">Parameters</h4>
        <form class="mb-6" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <div class="flex flex-col mb-4">
              <label class="mb-2 uppercase font-bold text-lg text-grey-darkest" for="species">Species</label>
              <select class="border py-2 px-3 text-grey-darkest" name="species">
                <?php
                    foreach ($factory->getTrees() as $tree) {
                        echo "<option value='{$tree}'>".ucfirst($tree).'</option>';
                    }
                ?>
            </select>
          </div>
          <div class="flex flex-col mb-4">
            <label class="mb-2 uppercase font-bold text-lg text-grey-darkest" for="height">Height *</label>
            <input class="border py-2 px-3 text-grey-darkest" type="text" name="height" placeholder="height" required value="<?php echo $currentHeight; ?>"">
          </div>
          <div class="flex flex-col mb-4">
            <label class="mb-2 uppercase font-bold text-lg text-grey-darkest" for="circumference">Circumference *</label>
            <input class="border py-2 px-3 text-grey-darkest" type="text" name="circumference" placeholder="circumference" required value="<?php echo $currentCircumference; ?>"">
          </div>
          <hr class="my-5">
          <div class="flex flex-col mb-4">
            <label class="mb-2 uppercase font-bold text-lg text-grey-darkest" for="observed">Observed</label>
            <input class="border py-2 px-3 text-grey-darkest" type="text" name="observed" placeholder="eg 1990-01-04" value="<?php echo $currentObservedDate; ?>"">
          </div>
          <div class="flex flex-col mb-4">
            <label class="mb-2 uppercase font-bold text-lg text-grey-darkest" for="age">Age</label>
            <input class="border py-2 px-3 text-grey-darkest" type="text" name="age" placeholder="age" value="<?php echo $currentAge; ?>"">
          </div>
          <button class="block bg-green-600 hover:bg-green-700 uppercase text-lg text-white mx-auto p-4 rounded" type="submit">Build Tree Data</button>
        </form>
      </div>
    </div>
  </div>

  <div class="lg:col-span-4">
<?php
try {
                    $treeData = $factory->build($species, $treeParameters);

                    echo '<div class="w-full bg-white rounded shadow-lg p-8">';

                    echo '<table class="table-auto bg-white border w-full">';
                    echo "<tr class='font-black text-white bg-green-800'><td class='p-2' colspan='2'>Tree Information</td></tr>";
                    echo "<tr class='border'><td class='p-3 w-1/2'>Tree Name</td><td class='p-3'>{$treeData->getPopularName()}</td></tr>";
                    echo "<tr class='border'><td class='p-3'>Family</td><td class='p-3'>{$treeData->getFamilyName()}</td></tr>";
                    echo '<tr class="border"><td class="p-3">Common Name(s)</td><td class="p-3">';
                    foreach ($treeData->getCommonNames() as $name) {
                        echo $name.'<br>';
                    }
                    echo '</td></tr>';
                    echo '<tr class="border"><td class="p-3">Scientific Name(s)</td><td class="p-3">';
                    foreach ($treeData->getScientificName() as $name) {
                        echo $name.'<br>';
                    }
                    echo '</td></tr>';
                    echo "<tr class='border'><td class='p-3'>Habitat</td><td class='p-3'>{$treeData->getHabitat()}</td></tr>";
                    echo "<tr class='font-black text-white bg-green-800'><td class='p-2' colspan='2'>Tree Attributes</td></tr>";
                    echo "<tr class='border'><td class='p-3'>Current Age</td><td class='p-3'>{$treeData->getAge()}</td></tr>";
                    echo "<tr class='border'><td class='p-3'>Height</td><td class='p-3'>{$treeData->getHeight()}</td></tr>";
                    echo "<tr class='border'><td class='p-3'>Circumference:</td><td class='p-3'>{$treeData->getCircumference()}</td></tr>";
                    echo "<tr class='border'><td class='p-3'>Diameter:</td><td class='p-3'>{$treeData->getDiameter()}</td></tr>";
                    echo "<tr class='border'><td class='p-3'>Green Weight:</td><td class='p-3'>{$treeData->getWeight()}</td></tr>";
                    echo "<tr class='border'><td class='p-3'>Dry Weight:</td><td class='p-3'>{$treeData->getDryWeight()}</td></tr>";

                    echo "<tr class='font-black text-white bg-green-800'><td class='p-2' colspan='2'>Carbon Data</td></tr>";
                    echo "<tr class='border'><td class='p-3'>Carbon in tree:</td><td class='p-3'>{$treeData->getCarbonWeight()}</td></tr>";
                    echo "<tr class='border'><td class='p-3'>CO2 Sequestered per year:</td><td class='p-3'>{$treeData->getCO2SequestrationPerYear()}</td></tr>";
                    echo "<tr class='border'><td class='p-3'>CO2 Sequestered to date:</td><td class='p-3'>{$treeData->getCO2SequestrationToDate()}</td></tr>";

                    echo "<tr class='font-black text-white bg-green-800'><td class='p-2' colspan='2'>Growth Rates &amp; Max Values</td></tr>";
                    echo "<tr class='border'><td class='p-3'>Actual Average Height Growth Rate:</td><td class='p-3'>{$treeData->getActualAnnualHeightGrowthRate()}</td></tr>";
                    echo "<tr class='border'><td class='p-3'>Default Average Height Growth Rate:</td><td class='p-3'>{$treeData->getAverageAnnualHeightGrowthRate()}</td></tr>";
                    echo "<tr class='border'><td class='p-3'>Actual Average Circumference Growth Rate:</td><td class='p-3'>{$treeData->getActualAverageCircumferenceGrowthRate()}</td></tr>";
                    echo "<tr class='border'><td class='p-3'>Default Average Circumference Growth Rates:</td><td class='p-3'>{$treeData->getAverageAnnualCircumferenceGrowthRate()}</td></tr>";
                    echo "<tr class='border'><td class='p-3'>Max Height:</td><td class='p-3'>{$treeData->getMaxHeight()} cm</td></tr>";
                    echo "<tr class='border'><td class='p-3'>Max Circumference:</td><td class='p-3'>{$treeData->getMaxCircumference()} cm</td></tr>";

                    echo '<tr class="border"><td colspan="2" class="p-3"><h4 class="font-bold">Build Log:</h4>';
                    echo '<p class="mb-5">Carbon results will be based on tree size calculated from:</p>';
                    foreach ($treeData->getBuildLog() as $logMessage) {
                        echo '- '.$logMessage.'<br>';
                    }
                    echo '</td></tr>';
                    echo '</table>';
                    echo '</div>';
                } catch (\Exception $exception) {
                    echo $exception->getMessage();
                }
?>
    </div>
</div>


</div>
</body>
</html>