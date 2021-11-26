<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\Strategies\AgeFromCircumference;
use CowshedWorks\Trees\Tests\TestTreeFactory;
use PHPUnit\Framework\TestCase;

class AgeFromCircumferenceTest extends TestCase
{
    use TestTreeFactory;

    /**
     * @test
     */
    public function it_will_calculate_the_age_from_the_circumference(): void
    {
        $factory = $this->getTreeDataFactory();
        $treeData = $factory->build('testTree', [
            'height'        => '3000cm',
            'circumference' => '170cm',
        ]);

        (new AgeFromCircumference())->execute($treeData);

        $this->assertEquals('70.83 years', (string) $treeData->getEstimatedAge());
    }
}
