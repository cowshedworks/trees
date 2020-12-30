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
    public function a_tree_calculator_will_guess_the_age_and_height_when_circumference_is_provided(): void
    {
        $configLoader = new ConfigLoader();
        $presentState = new TreeAttributes(
            $configLoader->getConfigFor('alder'),
            ['circumference' => '33cm']
        );

        $this->assertEquals($presentState->getAge(), 1);
        $this->assertEquals($presentState->getHeight(), 1);
        $this->assertEquals($presentState->getCircumference(), 1);
    }
}
