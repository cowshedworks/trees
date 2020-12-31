<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use CowshedWorks\Trees\Age;
use PHPUnit\Framework\TestCase;

class AgeUnitValueTest extends TestCase
{
    /**
     * @test
     */
    public function age_unit_value_does_not_convert_years(): void
    {
        $age = new Age(32, 'years');

        $this->assertEquals('32 years', $age->getDescription());
    }

    /**
     * @test
     */
    public function age_unit_value_will_convert_months_to_years(): void
    {
        $age = new Age(36, 'months');

        $this->assertEquals('3 years', $age->getDescription());
    }

    /**
     * @test
     */
    public function age_unit_value_will_convert_days_to_years(): void
    {
        $age = new Age(400, 'days');

        $this->assertEquals('1.1 years', $age->getDescription());
    }
}
