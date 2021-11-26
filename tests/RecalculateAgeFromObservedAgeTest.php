<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\Strategies\RecalculateAgeFromObservedAge;
use DateInterval;
use DateTime;
use PHPUnit\Framework\TestCase;

class RecalculateAgeFromObservedAgeTest extends TestCase
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
            'height'        => '3000cm',
            'circumference' => '170cm',
            'observed'      => $observationDate,
        ]);

        (new RecalculateAgeFromObservedAge())->execute($treeData);

        $this->assertEquals('131.58 years', (string) $treeData->getAge());
    }
}
