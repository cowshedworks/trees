<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\TreeCalculatorFactory;
use PHPUnit\Framework\TestCase;

class TreeCalculatorTest extends TestCase
{
    /**
     * @test
     */
    public function a_tree_calculator_will_guess_the_age_and_height_when_circumference_is_provided(): void
    {
        $factory = new TreeCalculatorFactory();

        $calculator = $factory->alder(['circumference' => '33cm']);

        $this->assertEquals($calculator->getAge(), 1);
        $this->assertEquals($calculator->getHeight(), 1);
    }
}
