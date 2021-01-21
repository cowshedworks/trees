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
    public function co2_sequestration_can_be_calculated(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree', [
            'age'           => '10years',
            'circumference' => '8in',
            'height'        => '15ft',
        ]);

        $this->assertEquals('17.63 kg', $data->getCO2SequestrationToDate()->describe());
        $this->assertEquals('1.76 kg', $data->getCO2SequestrationPerYear()->describe());
    }
}
