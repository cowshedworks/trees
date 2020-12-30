<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\TreeCalculatorFactory;
use Exception;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @test
     */
    public function the_factory_can_list_available_tree_calculators(): void
    {
        $factory = new TreeCalculatorFactory();

        $this->assertContains(
            'alder',
            $factory->getTrees(),
        );
    }

    /**
     * @test
     */
    public function the_factory_will_not_build_a_calculator_when_no_params_are_provided(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No config provided');

        $factory = new TreeCalculatorFactory();
        $calculator = $factory->alder();
    }

    /**
     * @test
     */
    public function the_factory_will_not_build_a_calculator_when_unknown_params_are_provided(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot build alder calculator without one of these parameters: age, height, circumference');

        $factory = new TreeCalculatorFactory();
        $calculator = $factory->alder(['wat' => '100cm']);
    }

    /**
     * @test
     */
    public function the_factory_can_get_a_calculator(): void
    {
        $factory = new TreeCalculatorFactory();

        $calculator = $factory->alder(['circumference' => '33cm']);

        $this->assertNotNull($calculator);
        $this->assertEquals($calculator->getPopularName(), 'Alder');
        $this->assertEquals($calculator->getCommonNames(), ['Alder', 'Common Alder', 'Black Alder', 'European Alder']);
        $this->assertEquals($calculator->getScientificName(), ['Alnus glutinosa']);
    }
}
