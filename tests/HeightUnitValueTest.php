<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\Height;
use PHPUnit\Framework\TestCase;

class HeightUnitValueTest extends TestCase
{
    /**
     * @test
     */
    public function height_unit_value_does_not_convert_cm(): void
    {
        $height = new Height(100, 'cm');

        $this->assertEquals('100 cm', $height->getDescription());
    }

    /**
     * @test
     */
    public function height_unit_value_will_convert_m_to_cm(): void
    {
        $height = new Height(40, 'm');

        $this->assertEquals('4000 cm', $height->getDescription());
    }

    /**
     * @test
     */
    public function height_unit_value_will_convert_inches_to_cm(): void
    {
        $height = new Height(25, 'in');

        $this->assertEquals('63.5 cm', $height->getDescription());
    }
}
