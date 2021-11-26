<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\Strategies\AgeFromHeight;
use PHPUnit\Framework\TestCase;

class DiameterFromCircumferenceTest extends TestCase
{
    use TestTreeFactory;

    /**
     * @test
     */
    public function it_will_calculate_the_diameter_from_the_circumference(): void
    {
        $factory = $this->getTreeDataFactory();
        $treeData = $factory->build('testTree', [
            'height'        => '3000cm',
            'circumference' => '170cm',
        ]);

        (new AgeFromHeight())->execute($treeData);

        $this->assertEquals('54.11 cm', (string) $treeData->getDiameter());
    }
}
