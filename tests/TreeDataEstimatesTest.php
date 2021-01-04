<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use PHPUnit\Framework\TestCase;

class TreeDataEstimatesTest extends TestCase
{
    use TestTreeFactory;

    /**
     * @test
     */
    public function growth_rates_are_expected(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['height' => '10m']);

        $this->assertEquals('60 cm', $data->getAverageAnnualHeightGrowthRate()->describe());
        $this->assertEquals('2.5 cm', $data->getAverageAnnualCircumferenceGrowthRate()->describe());
    }

    /**
     * @test
     */
    public function from_height_is_reasonable_estimate(): void
    {
        $factory = $this->getTreeDataFactory();

        $data = $factory->testTree(['height' => '10m']);

        $this->assertEquals('1000 cm', $data->getHeight()->describe());
        $this->assertEquals('8.69 years', $data->getAge()->describe());
        $this->assertEquals('21.73 cm', $data->getCircumference()->describe());
        $this->assertEquals('6.92 cm', $data->getDiameter()->describe());
    }

    /**
     * @test
     */
    public function from_circumference_is_reasonable_estimate(): void
    {
        $factory = $this->getTreeDataFactory();

        $data = $factory->testTree(['circumference' => '42cm']);

        $this->assertEquals('1419.67 cm', $data->getHeight()->describe());
        $this->assertEquals('16.8 years', $data->getAge()->describe());
        $this->assertEquals('42 cm', $data->getCircumference()->describe());
        $this->assertEquals('13.37 cm', $data->getDiameter()->describe());
    }

    /**
     * @test
     */
    public function from_age_is_reasonable_estimate(): void
    {
        $factory = $this->getTreeDataFactory();

        $data = $factory->testTree(['age' => '17years']);

        $this->assertEquals('1431.27 cm', $data->getHeight()->describe());
        $this->assertEquals('17 years', $data->getAge()->describe());
        $this->assertEquals('42.5 cm', $data->getCircumference()->describe());
        $this->assertEquals('13.53 cm', $data->getDiameter()->describe());
    }
}
