<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use PHPUnit\Framework\TestCase;

class TreeDataCalculationsTest extends TestCase
{
    use TestTreeFactory;

    /**
     * @test
     */
    public function growth_rates_are_expected(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['height' => '10m']);

        $heightGrowthRate = $data->getSpeciesDataUnitValue(
            'attributes.growth-rate.annual-average-height',
            'height'
        );
        $this->assertEquals('60 cm', $heightGrowthRate->getDescription());

        $circumferenceGrowthRate = $data->getSpeciesDataUnitValue(
            'attributes.growth-rate.annual-average-circumference',
            'height'
        );
        $this->assertEquals('2.5 cm', $circumferenceGrowthRate->getDescription());
    }

    /**
     * @test
     */
    public function from_height_is_reasonable_estimate(): void
    {
        $factory = $this->getTreeDataFactory();

        $data = $factory->testTree(['height' => '10m']);

        $this->assertEquals('1000 cm', $data->describeHeight());
        $this->assertEquals('16.67 years', $data->describeAge());
        $this->assertEquals('41.68 cm', $data->describeCircumference());
        $this->assertEquals('13.27 cm', $data->describeDiameter());
    }

    /**
     * @test
     */
    public function from_circumference_is_reasonable_estimate(): void
    {
        $factory = $this->getTreeDataFactory();

        $data = $factory->testTree(['circumference' => '42cm']);

        $this->assertEquals('1008 cm', $data->describeHeight());
        $this->assertEquals('16.8 years', $data->describeAge());
        $this->assertEquals('42 cm', $data->describeCircumference());
        $this->assertEquals('13.37 cm', $data->describeDiameter());
    }

    /**
     * @test
     */
    public function from_age_is_reasonable_estimate(): void
    {
        $factory = $this->getTreeDataFactory();

        $data = $factory->testTree(['age' => '17years']);

        $this->assertEquals('1020 cm', $data->describeHeight());
        $this->assertEquals('17 years', $data->describeAge());
        $this->assertEquals('42.5 cm', $data->describeCircumference());
        $this->assertEquals('13.53 cm', $data->describeDiameter());
    }
}
