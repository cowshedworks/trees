<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use PHPUnit\Framework\TestCase;

class TreeDataCalculationsTest extends TestCase
{
    use TestTreeFactory;

    /**
     * @test
     */
    public function tree_data_will_be_calculated_from_age(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree(['age' => '75years']);

        $this->assertEquals('75 years', $data->describeAge());
        $this->assertEquals('4500 cm', $data->describeHeight());
        $this->assertEquals('187.5 cm', $data->describeCircumference());
    }
}
