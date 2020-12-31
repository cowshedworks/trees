<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\TreeDataFactory;
use Exception;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @test
     */
    public function the_factory_can_list_available_tree_data(): void
    {
        $factory = new TreeDataFactory();

        $this->assertContains(
            'alder',
            $factory->getTrees(),
        );
    }

    /**
     * @test
     */
    public function the_factory_will_not_build_when_no_params_are_provided(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No config provided');

        $factory = new TreeDataFactory();
        $data = $factory->alder();
    }

    /**
     * @test
     */
    public function the_factory_will_not_build_when_unknown_params_are_provided(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot build alder data without one of these parameters: age, height, circumference');

        $factory = new TreeDataFactory();
        $data = $factory->alder(['wat' => '100cm']);
    }

    /**
     * @test
     */
    public function the_factory_can_get_tree_data(): void
    {
        $factory = new TreeDataFactory();

        $data = $factory->alder(['circumference' => '33cm']);

        $this->assertNotNull($data);
        $this->assertEquals('Alder', $data->getPopularName());
        $this->assertEquals(['Alder', 'Common Alder', 'Black Alder', 'European Alder'], $data->getCommonNames());
        $this->assertEquals(['Alnus glutinosa'], $data->getScientificName());
    }
}
