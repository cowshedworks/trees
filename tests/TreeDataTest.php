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

        $this->assertEquals($data->describeAge(), '13.2 years');
        $this->assertEquals($data->describeHeight(), '792 cm');
        $this->assertEquals($data->describeCircumference(), '33 cm');

        $factory = new TreeDataFactory();
        $data = $factory->alder(['circumference' => '33 cm']);

        $this->assertEquals($data->describeAge(), '13.2 years');
        $this->assertEquals($data->describeHeight(), '792 cm');
        $this->assertEquals($data->describeCircumference(), '33 cm');
    }

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_circumference(): void
    {
        $factory = new TreeDataFactory();
        $data = $factory->alder(['circumference' => '33cm']);

        $this->assertEquals($data->describeAge(), '13.2 years');
        $this->assertEquals($data->describeHeight(), '792 cm');
        $this->assertEquals($data->describeCircumference(), '33 cm');
    }

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_age(): void
    {
        $factory = new TreeDataFactory();
        $data = $factory->alder(['age' => '75years']);

        $this->assertEquals($data->describeAge(), '75 years');
        $this->assertEquals($data->describeHeight(), '4500 cm');
        $this->assertEquals($data->describeCircumference(), '187.5 cm');
    }

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_height(): void
    {
        $factory = new TreeDataFactory();
        $data = $factory->alder(['height' => '10cm']);

        $this->assertEquals($data->describeAge(), '0.17 years');
        $this->assertEquals($data->describeHeight(), '10 cm');
        $this->assertEquals($data->describeCircumference(), '0.425 cm');
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

        $this->assertEquals($data->describeAge(), '40 years');
        $this->assertEquals($data->describeHeight(), '280 cm');
        $this->assertEquals($data->describeCircumference(), '33 cm');
    }

    /**
     * @test
     */
    public function co2_sequestration_per_year_can_be_calculated(): void
    {
        $factory = new TreeDataFactory();
        $data = $factory->alder(['circumference' => '33cm']);

        $this->assertEquals('10023 kg', $data->describeCO2SequestrationPerYear());
    }
}
