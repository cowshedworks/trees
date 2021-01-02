<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\UnitValues\Weight;
use PHPUnit\Framework\TestCase;

class WeightUnitValueTest extends TestCase
{
    /**
     * @test
     */
    public function weight_unit_value_does_not_convert_kg(): void
    {
        $weight = new Weight(20, 'kg');

        $this->assertEquals('20 kg', $weight->describe());
    }

    /**
     * @test
     */
    public function weight_unit_value_will_convert_lbs_to_kg(): void
    {
        $weight = new Weight(36, 'lbs');

        $this->assertEquals('16.33 kg', $weight->describe());
    }

    /**
     * @test
     */
    public function weight_unit_value_will_convert_grams_to_kg(): void
    {
        $weight = new Weight(200, 'grams');

        $this->assertEquals('0.2 kg', $weight->describe());
    }
}
