<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\Strategies\AgeFromHeight;
use CowshedWorks\Trees\Tests\TestTreeFactory;
use PHPUnit\Framework\TestCase;

class AgeFromHeightTest extends TestCase
{
    use TestTreeFactory;

    /**
     * @test
     */
    public function it_will_calculate_the_age_from_the_height(): void
    {
        $factory = $this->getTreeDataFactory();
        $treeData = $factory->build('testTree', [
            'height'        => '3000cm',
            'circumference' => '170cm',
        ]);

        (new AgeFromHeight())->execute($treeData);

        $this->assertEquals('71.54 years', (string) $treeData->getEstimatedAge());
    }
}
