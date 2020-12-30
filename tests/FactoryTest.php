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
}