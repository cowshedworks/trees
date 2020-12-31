<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

use CowshedWorks\Trees\Age;
use PHPUnit\Framework\TestCase;

class CircumferenceUnitValueTest extends TestCase
{
    /**
     * @test
     */
    public function circumference_unit_value_does_not_convert_years(): void
    {
        $age = new Circumference(45, 'cm');

        $this->assertEquals('45 cm', $age->getDescription());
    }

    /**
     * @test
     */
    public function circumference_unit_value_will_convert_months_to_years(): void
    {
        $age = new Circumference(1500, 'mm');

        $this->assertEquals('150 cm', $age->getDescription());
    }
}

