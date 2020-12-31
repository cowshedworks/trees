<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\Circumference;
use PHPUnit\Framework\TestCase;

class CircumferenceUnitValueTest extends TestCase
{
    /**
     * @test
     */
    public function circumference_unit_value_does_not_convert_years(): void
    {
        $circumference = new Circumference(45, 'cm');

        $this->assertEquals('45 cm', $circumference->getDescription());
    }

    /**
     * @test
     */
    public function circumference_unit_value_will_convert_mm_to_cm(): void
    {
        $circumference = new Circumference(1500, 'mm');

        $this->assertEquals('150 cm', $circumference->getDescription());
    }

    /**
     * @test
     */
    public function circumference_unit_value_will_convert_m_to_cm(): void
    {
        $circumference = new Circumference(0.2, 'm');

        $this->assertEquals('20 cm', $circumference->getDescription());
    }
}
