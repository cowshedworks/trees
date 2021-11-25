<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use PHPUnit\Framework\TestCase;

class TreeDataAgeEstimatesTest extends TestCase
{
    use TestTreeFactory;

    /**
     * @test
     */
    public function growth_rates_are_expected(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree', [
            'height'        => '10m',
            'circumference' => '5cm',
        ]);

        $this->assertEquals('65 cm', $data->getAverageAnnualHeightGrowthRate()->describe());
        $this->assertEquals('2.4 cm', $data->getAverageAnnualCircumferenceGrowthRate()->describe());
    }

    /**
     * @test
     */
    public function growth_rates_are_default_when_not_provided(): void
    {
        $factory = $this->getTreeDataFactoryWithEmptyData();
        $data = $factory->build('testTree', [
            'height'        => '10m',
            'circumference' => '5cm',
        ]);

        $this->assertEquals('60 cm', $data->getAverageAnnualHeightGrowthRate()->describe());
        $this->assertEquals('2.5 cm', $data->getAverageAnnualCircumferenceGrowthRate()->describe());
    }

    /**
     * @test
     */
    public function age_from_height_is_reasonable_estimate(): void
    {
        $factory = $this->getTreeDataFactory();

        $data = $factory->build('testTree', [
            'height'        => '10m',
            'circumference' => '21cm',
        ]);

        $this->assertEquals('8.69 years', $data->getAge()->describe());
    }

    /**
     * @test
     */
    public function age_from_circumference_is_reasonable_estimate(): void
    {
        $factory = $this->getTreeDataFactory();

        $data = $factory->build('testTree', [
            'height'        => '1800cm',
            'circumference' => '42cm',
        ]);

        $this->assertEquals('15.46 years', $data->getAge()->describe());
    }
}
