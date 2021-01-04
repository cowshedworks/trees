<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use DateTime;
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
        $this->assertEquals('1204.59 cm', $data->getHeight()->describe());
        $this->assertEquals('33 cm', $data->getCircumference()->describe());
        $this->assertEquals('10.5 cm', $data->getDiameter()->describe());

        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['circumference' => '33 cm']);

        $this->assertEquals('13.2 years', $data->getAge()->describe());
        $this->assertEquals('1204.59 cm', $data->getHeight()->describe());
        $this->assertEquals('33 cm', $data->getCircumference()->describe());
        $this->assertEquals('10.5 cm', $data->getDiameter()->describe());
    }

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_circumference(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['circumference' => '33cm']);

        $this->assertEquals('13.2 years', $data->getAge()->describe());
        $this->assertEquals('1204.59 cm', $data->getHeight()->describe());
        $this->assertEquals('33 cm', $data->getCircumference()->describe());
        $this->assertEquals('10.5 cm', $data->getDiameter()->describe());
    }

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_diameter(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['diameter' => '10cm']);

        $this->assertEquals('12.57 years', $data->getAge()->describe());
        $this->assertEquals('1165.74 cm', $data->getHeight()->describe());
        $this->assertEquals('31.43 cm', $data->getCircumference()->describe());
        $this->assertEquals('10 cm', $data->getDiameter()->describe());
    }

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_age(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['age' => '75years']);

        $this->assertEquals('75 years', $data->getAge()->describe());
        $this->assertEquals('3252.55 cm', $data->getHeight()->describe());
        $this->assertEquals('187.5 cm', $data->getCircumference()->describe());
        $this->assertEquals('59.68 cm', $data->getDiameter()->describe());
    }

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_height(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['height' => '10cm']);

        $this->assertEquals('0.25 years', $data->getAge()->describe());
        $this->assertEquals('10 cm', $data->getHeight()->describe());
        $this->assertEquals('0.63 cm', $data->getCircumference()->describe());
        $this->assertEquals('0.2 cm', $data->getDiameter()->describe());
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
        $this->assertEquals('10.5 cm', $data->getDiameter()->describe());
    }

    /**
     * @test
     */
    public function tree_observation_timestamp_is_set_to_today_if_not_provided(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['height' => '10cm']);

        $today = new DateTime();

        $this->assertEquals($today->format('Y-m-d'), $data->getObservedDate()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function tree_observation_timestamp_is_set_to_date_if_provided(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree([
            'height'   => '10cm',
            'observed' => '1977-11-21',
        ]);

        $this->assertEquals('1977-11-21', $data->getObservedDate()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function tree_age_uses_observation_date(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree([
            'height'   => '10cm',
            'observed' => '1977-01-04',
        ]);

        $this->assertEquals('1977-01-04', $data->getObservedDate()->format('Y-m-d'));
        $this->assertEquals('44.28 years', $data->getAge()->describe());
        $this->assertEquals('2671.01 cm', $data->getHeight()->describe());
        $this->assertEquals('110.7 cm', $data->getCircumference()->describe());
        $this->assertEquals('35.24 cm', $data->getDiameter()->describe());
    }
}
