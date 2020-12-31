<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\TreeDataFactory;
use PHPUnit\Framework\TestCase;

class TreeDataTest extends TestCase
{
    /**
     * @test
     */
    public function tree_data_will_be_calculated_regardless_of_unit_spacing(): void
    {
        $factory = new TreeDataFactory();
        $data = $factory->alder(['circumference' => '33cm']);

        $this->assertEquals('13.2 years', $data->describeAge());
        $this->assertEquals('792 cm', $data->describeHeight());
        $this->assertEquals('33 cm', $data->describeCircumference());

        $factory = new TreeDataFactory();
        $data = $factory->alder(['circumference' => '33 cm']);

        $this->assertEquals('13.2 years', $data->describeAge());
        $this->assertEquals('792 cm', $data->describeHeight());
        $this->assertEquals('33 cm', $data->describeCircumference());
    }

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_circumference(): void
    {
        $factory = new TreeDataFactory();
        $data = $factory->alder(['circumference' => '33cm']);

        $this->assertEquals('13.2 years', $data->describeAge());
        $this->assertEquals('792 cm', $data->describeHeight());
        $this->assertEquals('33 cm', $data->describeCircumference());
    }

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_age(): void
    {
        $factory = new TreeDataFactory();
        $data = $factory->alder(['age' => '75years']);

        $this->assertEquals('75 years', $data->describeAge());
        $this->assertEquals('4500 cm', $data->describeHeight());
        $this->assertEquals('187.5 cm', $data->describeCircumference());
    }

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_height(): void
    {
        $factory = new TreeDataFactory();
        $data = $factory->alder(['height' => '10cm']);

        $this->assertEquals('0.17 years', $data->describeAge());
        $this->assertEquals('10 cm', $data->describeHeight());
        $this->assertEquals('0.43 cm', $data->describeCircumference());
    }

    /**
     * @test
     */
    public function all_tree_data_can_be_overridden(): void
    {
        $factory = new TreeDataFactory();
        $data = $factory->alder([
            'circumference' => '33cm',
            'age'           => '40 years',
            'height'        => '280cm',
        ]);

        $this->assertEquals('40 years', $data->describeAge());
        $this->assertEquals('280 cm', $data->describeHeight());
        $this->assertEquals('33 cm', $data->describeCircumference());
    }

    /**
     * @test
     */
    public function co2_sequestration_per_year_can_be_calculated(): void
    {
        $factory = new TreeDataFactory();
        $data = $factory->alder([
            'age'      => '10years',
            'diameter' => '8in',
            'height'   => '15feet',
        ]);

        $this->assertEquals('172.87 kg', $data->describeCO2SequestrationToDate());
        $this->assertEquals('17.29 kg', $data->describeCO2SequestrationPerYear());
    }
}
