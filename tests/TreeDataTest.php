<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use PHPUnit\Framework\TestCase;

class TreeDataTest extends TestCase
{
    use TestTreeFactory;

    /**
     * @test
     */
    public function tree_data_will_be_calculated_regardless_of_unit_spacing(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['circumference' => '33cm']);

        $this->assertEquals('13.2 years', $data->getAge()->describe());
        $this->assertEquals('792 cm', $data->getHeight()->describe());
        $this->assertEquals('33 cm', $data->getCircumference()->describe());

        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['circumference' => '33 cm']);

        $this->assertEquals('13.2 years', $data->getAge()->describe());
        $this->assertEquals('792 cm', $data->getHeight()->describe());
        $this->assertEquals('33 cm', $data->getCircumference()->describe());
    }

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_circumference(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['circumference' => '33cm']);

        $this->assertEquals('13.2 years', $data->getAge()->describe());
        $this->assertEquals('792 cm', $data->getHeight()->describe());
        $this->assertEquals('33 cm', $data->getCircumference()->describe());
    }

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_age(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['age' => '75years']);

        $this->assertEquals('75 years', $data->getAge()->describe());
        $this->assertEquals('4500 cm', $data->getHeight()->describe());
        $this->assertEquals('187.5 cm', $data->getCircumference()->describe());
    }

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_height(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['height' => '10cm']);

        $this->assertEquals('0.17 years', $data->getAge()->describe());
        $this->assertEquals('10 cm', $data->getHeight()->describe());
        $this->assertEquals('0.43 cm', $data->getCircumference()->describe());
    }

    /**
     * @test
     */
    public function all_tree_data_can_be_overridden(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree([
            'circumference' => '33cm',
            'age'           => '40 years',
            'height'        => '280cm',
        ]);

        $this->assertEquals('40 years', $data->getAge()->describe());
        $this->assertEquals('280 cm', $data->getHeight()->describe());
        $this->assertEquals('33 cm', $data->getCircumference()->describe());
    }
}
