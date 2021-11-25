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
$currentObservedDate = $_GET['observed'] ?? '2001-01-01';

$treeParameters = [
    'circumference'   => $currentCircumference,
    'height'          => $currentHeight,
    'observed'        => $currentObservedDate,
];

?>

<h1 class="text-4xl font-black text-white text-center mb-5 text-shadow-lg">Tree Data Testing Tool</h1>

<div class="grid grid-cols-1 gap-4 lg:grid-cols-8 p-4">

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
          <button class="block bg-green-600 hover:bg-green-700 uppercase text-lg text-white mx-auto p-4 rounded" type="submit">Build Tree Data</button>
        </form>
      </div>
    </div>
  </div>
<?php
try {
  $treeData = $factory->build($species, $treeParameters);

  echo '<div class="lg:col-span-2 text-center">';
  echo '<div class="w-full bg-white rounded shadow-lg p-8">';
  echo "<h3 class='font-bold text-lg'>Calculated Tree Data</h3>";
  echo "<p class='mb-2'>Estimated Age <br><span class='text-xl'>{$treeData->getEstimatedAge()}</span></p>";
  echo "<p class='mb-2'>Height <br><span class='text-xl'>{$treeData->getHeight()}</span></p>";
  echo "<p class='mb-2'>Circumference<br><span class='text-xl'>{$treeData->getCircumference()}</span></p>";
  echo "<p class='mb-2'>Diameter<br><span class='text-xl'>{$treeData->getDiameter()}</span></p>";
  echo "<p class='mb-2'>Green Weight<br><span class='text-xl'>{$treeData->getWeight()}</span></p>";
  echo "<p class='mb-2'>Dry Weight<br><span class='text-xl'>{$treeData->getDryWeight()}</span></p>";
  echo '</div>';
  echo '</div>';

  echo '<div class="lg:col-span-2 text-center">';
  echo '<div class="w-full bg-white rounded shadow-lg p-8">';
  echo "<h3 class='font-bold text-lg'>Growth Rates</h3>";
  echo "<p class='mb-2'>Actual Average Height Growth Rate<br><span class='text-xl'>{$treeData->getActualAnnualHeightGrowthRate()}</span></p>";
  echo "<p class='mb-2'>Default Average Height Growth Rate<br><span class='text-xl'>{$treeData->getAverageAnnualHeightGrowthRate()}</span></p>";
  echo "<p class='mb-2'>Actual Average Circumference Growth Rate<br><span class='text-xl'>{$treeData->getActualAverageCircumferenceGrowthRate()}</span></p>";
  echo "<p class='mb-2'>Default Average Circumference Growth Rates<br><span class='text-xl'>{$treeData->getAverageAnnualCircumferenceGrowthRate()}</span></p>";
  echo "<p class='mb-2'>Max Height<br><span class='text-xl'>{$treeData->getMaxHeight()} cm</span></p>";
  echo "<p class='mb-2'>Max Circumference<br><span class='text-xl'>{$treeData->getMaxCircumference()} cm</span></p>";
  echo "<h4>Carbon Data</h4>";
  echo "<p class='mb-2'>Carbon in tree<br><span class='text-xl'>{$treeData->getCarbonWeight()}</span></p>";
  echo "<p class='mb-2'>CO2 Sequestered per year<br><span class='text-xl'>{$treeData->getCO2SequestrationPerYear()}</span></p>";
  echo "<p class='mb-2'>CO2 Sequestered to date<br><span class='text-xl'>{$treeData->getCO2SequestrationToDate()}</span></p>";
  echo '</div>';
  echo '</div>';

  echo '<div class="lg:col-span-2 text-center">';
  echo '<div class="w-full bg-white rounded shadow-lg p-8">';
  echo "<h3 class='font-bold text-lg'>Species Data</h3>";
  echo "<p class='mb-2'>Tree Name<br><span class='text-xl'>{$treeData->getPopularName()}</span></p>";
  echo "<p class='mb-2'>Family<br><span class='text-xl'>{$treeData->getFamilyName()}</span></p>";
  echo "<p class='mb-2'>Common Name(s)<br><span class='text-xl'>";
  foreach ($treeData->getCommonNames() as $name) {
      echo $name.'<br>';
  }
  echo '</span><br>';
  echo "<p class='mb-2'>Scientific Name(s)<br><span class='text-xl'>";
  foreach ($treeData->getScientificName() as $name) {
      echo $name.'<br>';
  }
  echo '</span></p>';
  echo "<p class='mb-2'>Habitat<br>{$treeData->getHabitat()}</p>";
  echo '<hr class="my-5">';
  echo '<h4 class="font-bold text-lg">Build Log:</h4>';
  echo '<p class="mb-5">Carbon results will be based on tree size calculated from:</p>';
  foreach ($treeData->getBuildLog() as $logMessage) {
      echo '- '.$logMessage.'<br>';
  }
  echo '</div>';
  echo '</div>';

  } catch (\Exception $exception) {
  echo $exception->getMessage();
  }
?>
</div>
</div>
</body>
</html>