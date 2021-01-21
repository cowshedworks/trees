<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use ArgumentCountError;
use CowshedWorks\Trees\ConfigLoader;
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
        $this->expectException(ArgumentCountError::class);

        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree');
    }

    /**
     * @test
     */
    public function the_factory_will_not_build_when_unknown_params_are_provided(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot build testTree data without at least these parameters: height, circumference');

        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree', ['wat' => '100cm']);
    }

    /**
     * @test
     */
    public function the_factory_can_get_tree_data(): void
    {
        $factory = $this->getTreeDataFactory();

        $data = $factory->build(
            'testTree',
            [
                'circumference' => '33cm',
                'height'        => '2000cm',
            ]
        );

        $this->assertNotNull($data);
        $this->assertEquals('Test Tree', $data->getPopularName());
        $this->assertEquals(['Test Tree', 'Common Test Tree', 'Black Test Tree', 'European Test Tree'], $data->getCommonNames());
        $this->assertEquals(['TestTree glutinosa'], $data->getScientificName());
    }

    /**
     * @test
     */
    public function the_factory_can_build_tree_data_with_a_provided_species_config_file(): void
    {
        $factory = $this->getTreeDataFactory();

        $configLoader = new ConfigLoader();
        $configLoader->setDataDir(__DIR__.'/data');
        $treeConfig = $configLoader->getConfigFor('testTree');

        $data = $factory->buildFromConfig(
            $treeConfig,
            [
                'circumference' => '33cm',
                'height'        => '2000cm',
            ]
        );

        $this->assertNotNull($data);
        $this->assertEquals('Test Tree', $data->getPopularName());
        $this->assertEquals(['Test Tree', 'Common Test Tree', 'Black Test Tree', 'European Test Tree'], $data->getCommonNames());
        $this->assertEquals(['TestTree glutinosa'], $data->getScientificName());
    }

    /**
     * @test
     */
    public function the_factory_can_get_species_file_data(): void
    {
        $factory = $this->getTreeDataFactory();
        $fileData = $factory->getSpeciesFileData('testTree');

        $this->assertEquals('Test Tree', $fileData['name']['popular']);
    }
}
