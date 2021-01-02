<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use PHPUnit\Framework\TestCase;

class TreeDataCO2Test extends TestCase
{
    use TestTreeFactory;

    /**
     * @test
     */
    public function co2_sequestration_per_year_can_be_calculated(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree([
            'age'      => '10years',
            'diameter' => '8in',
            'height'   => '15feet',
        ]);

        $this->assertEquals('172.87 kg', $data->describeCO2SequestrationToDate());
        $this->assertEquals('17.29 kg', $data->describeCO2SequestrationPerYear());
    }
}
