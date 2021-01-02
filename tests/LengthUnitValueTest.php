<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\UnitValues\Length;
use PHPUnit\Framework\TestCase;

class LengthUnitValueTest extends TestCase
{
    /**
     * @test
     */
    public function length_unit_value_does_not_convert_years(): void
    {
        $length = new Length(45, 'cm');

        $this->assertEquals('45 cm', $length->describe());
    }

    /**
     * @test
     */
    public function length_unit_value_will_convert_mm_to_cm(): void
    {
        $length = new Length(1500, 'mm');

        $this->assertEquals('150 cm', $length->describe());
    }

    /**
     * @test
     */
    public function length_unit_value_will_convert_m_to_cm(): void
    {
        $length = new Length(0.2, 'm');

        $this->assertEquals('20 cm', $length->describe());
    }

    /**
     * @test
     */
    public function length_unit_value_will_convert_in_to_cm(): void
    {
        $length = new Length(10, 'in');

        $this->assertEquals('25.4 cm', $length->describe());
    }
}
