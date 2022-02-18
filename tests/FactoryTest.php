<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\SpeciesDataLoader;
use DateTime;
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
    public function the_factory_can_get_tree_data_with_a_provided_today_date(): void
    {
        $factory = $this->getTreeDataFactory();

        $data = $factory->build(
            'testTree',
            [
                'circumference' => '33cm',
                'height'        => '2000cm',
            ],
            new DateTime()
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

        $speciesDataLoader = new SpeciesDataLoader();
        $speciesDataLoader->setDataDir(__DIR__.'/data');
        $speciesData = $speciesDataLoader->getDataFor('testTree');

        $data = $factory->buildFromSpeciesDataFile(
            $speciesData,
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

        $this->assertEquals('Test Tree', $fileData->get('name.popular'));
    }

    /**
     * @test
     */
    public function it_allows_fluent_setting_of_parameters(): void
    {
        $factory = $this->getTreeDataFactory();

        $treeData = $factory->circumference('10cm')->height('10m')->build('testTree');

        $this->assertEquals('10 cm', (string) $treeData->getCircumference());
        $this->assertEquals('1000 cm', (string) $treeData->getHeight());
    }
}
