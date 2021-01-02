<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use Exception;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    use TestTreeFactory;

    /**
     * @test
     */
    public function the_factory_can_list_available_tree_data(): void
    {
        $factory = $this->getTreeDataFactory();

        $this->assertContains(
            'testTree',
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

        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree();
    }

    /**
     * @test
     */
    public function the_factory_will_not_build_when_unknown_params_are_provided(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot build testTree data without one of these parameters: age, height, circumference');

        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['wat' => '100cm']);
    }

    /**
     * @test
     */
    public function the_factory_can_get_tree_data(): void
    {
        $factory = $this->getTreeDataFactory();

        $data = $factory->testTree(['circumference' => '33cm']);

        $this->assertNotNull($data);
        $this->assertEquals('Alder', $data->getPopularName());
        $this->assertEquals(['Alder', 'Common Alder', 'Black Alder', 'European Alder'], $data->getCommonNames());
        $this->assertEquals(['Alnus glutinosa'], $data->getScientificName());
    }
}
