<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\ConfigLoader;
use CowshedWorks\Trees\TreeAttributes;
use PHPUnit\Framework\TestCase;

class TreeAttributesTest extends TestCase
{
    /**
     * @test
     */
    public function attributes_will_be_calculated_from_circumference(): void
    {
        $treeAttributes = new TreeAttributes(
            (new ConfigLoader())->getConfigFor('alder'),
            ['circumference' => '33cm']
        );

        $this->assertEquals($treeAttributes->describeAge(), '13.2 years');
        $this->assertEquals($treeAttributes->describeHeight(), '792 cm');
        $this->assertEquals($treeAttributes->describeCircumference(), '33 cm');
    }

    /**
     * @test
     */
    public function attributes_will_be_calculated_from_age(): void
    {
        $treeAttributes = new TreeAttributes(
            (new ConfigLoader())->getConfigFor('alder'),
            ['age' => '75years']
        );

        $this->assertEquals($treeAttributes->describeAge(), '75 years');
        $this->assertEquals($treeAttributes->describeHeight(), '4500 cm');
        $this->assertEquals($treeAttributes->describeCircumference(), '187.5 cm');
    }

    /**
     * @test
     */
    public function attributes_will_be_calculated_from_height(): void
    {
        $treeAttributes = new TreeAttributes(
            (new ConfigLoader())->getConfigFor('alder'),
            ['height' => '10cm']
        );

        $this->assertEquals($treeAttributes->describeAge(), '0.17 years');
        $this->assertEquals($treeAttributes->describeHeight(), '10 cm');
        $this->assertEquals($treeAttributes->describeCircumference(), '0.425 cm');
    }
}
