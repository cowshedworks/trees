<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\Strategies\RecalculateCircumferenceFromAgeAndGrowthRate;
use DateInterval;
use DateTime;
use PHPUnit\Framework\TestCase;

class RecalculateCircumferenceFromAgeAndGrowthRateTest extends TestCase
{
    use TestTreeFactory;

    /**
     * @test
     */
    public function it_will_recalculate_the_age_from_the_observed_date(): void
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P30Y'));
        $observationDate = $date->format('Y-m-d');

        $factory = $this->getTreeDataFactory();
        $treeData = $factory->build('testTree', [
            'height'        => '300cm',
            'circumference' => '17cm',
            'observed'      => $observationDate,
        ]);

        (new RecalculateCircumferenceFromAgeAndGrowthRate())->execute($treeData);

        $this->assertEquals('161.1 cm', (string) $treeData->getCircumference());
    }
}
