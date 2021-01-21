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
            'circumference' => '33cm',
            'observed'      => '1977-11-21',
        ]);

        $this->assertEquals('1977-11-21', $data->getObservedDate()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function tree_age_uses_observation_date(): void
    {
        $this->markTestIncomplete();

        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree', [
            'height'   => '10cm',
            'observed' => '1977-01-04',
        ]);

        $this->assertEquals('1977-01-04', $data->getObservedDate()->format('Y-m-d'));
        $this->assertEquals('44.29 years', $data->getAge()->describe());
        $this->assertEquals('2608.7 cm', $data->getHeight()->describe());
        $this->assertEquals('110.73 cm', $data->getCircumference()->describe());
        $this->assertEquals('35.25 cm', $data->getDiameter()->describe());
    }
}
