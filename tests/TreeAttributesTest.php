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

        $this->assertEquals($treeAttributes->describeAge(), '13.2years');
        $this->assertEquals($treeAttributes->describeHeight(), '792cm');
        $this->assertEquals($treeAttributes->describeCircumference(), '33cm');
    }
}
