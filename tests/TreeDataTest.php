<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use DateInterval;
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
        $data = $factory->build('testTree', [
            'circumference' => '33cm',
            'height'        => '300cm',
        ]);

        $this->assertEquals('4.62 years', $data->getAge()->describe());
        $this->assertEquals('300 cm', $data->getHeight()->describe());
        $this->assertEquals('33 cm', $data->getCircumference()->describe());
        $this->assertEquals('10.5 cm', $data->getDiameter()->describe());

        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree', [
            'circumference' => '33 cm',
            'height'        => '300 cm',
        ]);

        $this->assertEquals('4.62 years', $data->getAge()->describe());
        $this->assertEquals('300 cm', $data->getHeight()->describe());
        $this->assertEquals('33 cm', $data->getCircumference()->describe());
        $this->assertEquals('10.5 cm', $data->getDiameter()->describe());
    }

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_circumference_and_height(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree', [
            'circumference' => '33cm',
            'height'        => '300 cm',
        ]);

        $this->assertEquals('4.62 years', $data->getAge()->describe());
        $this->assertEquals('300 cm', $data->getHeight()->describe());
        $this->assertEquals('33 cm', $data->getCircumference()->describe());
        $this->assertEquals('10.5 cm', $data->getDiameter()->describe());
    }

    /**
     * @test
     */
    public function all_tree_data_can_be_overridden(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree', [
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
        $data = $factory->build('testTree', [
            'circumference' => '33cm',
            'height'        => '300 cm',
        ]);

        $today = new DateTime();

        $this->assertEquals($today->format('Y-m-d'), $data->getObservedDate()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function tree_observation_timestamp_is_set_to_date_if_provided(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree', [
            'height'        => '300cm',
            'circumference' => '10cm',
            'observed'      => '1977-11-21',
        ]);

        $this->assertEquals('1977-11-21', $data->getObservedDate()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function calculates_using_uses_observation_date_if_provided(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree', [
            'height'        => '300cm',
            'circumference' => '10cm',
        ]);

        $this->assertEquals('4.62 years', $data->getAge()->describe());
        $this->assertEquals('300 cm', $data->getHeight()->describe());
        $this->assertEquals('10 cm', $data->getCircumference()->describe());
        $this->assertEquals('3.18 cm', $data->getDiameter()->describe());

        // Set an observation date of 30 years ago from now
        $date = new DateTime();
        $date->sub(new DateInterval('P30Y'));
        $observationDate = $date->format('Y-m-d');

        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree', [
            'height'        => '300cm',
            'circumference' => '10cm',
            'observed'      => $observationDate,
        ]);

        $this->assertEquals($observationDate, $data->getObservedDate()->format('Y-m-d'));
        $this->assertEquals('34.64 years', $data->getAge()->describe());
        $this->assertEquals('2251.42 cm', $data->getHeight()->describe());
        $this->assertEquals('83.14 cm', $data->getCircumference()->describe());
        $this->assertEquals('26.46 cm', $data->getDiameter()->describe());
    }
}
