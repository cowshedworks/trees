<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\TreeCalculatorFactory;
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
