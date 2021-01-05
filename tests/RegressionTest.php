<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Tests;

use PHPUnit\Framework\TestCase;

class RegressionTest extends TestCase
{
    use TestTreeFactory;

    /**
     * @test
     */
    public function age_from_height_regression(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->testTree([
            'height'   => '10cm',
        ]);

        $regression = $data
            ->getHeightAgeRegression()
            ->buildAgeFromHeight();

        $heightInputInM = 1;
        $expectedAgeOutput = 1;
        $this->assertEquals($expectedAgeOutput, round($regression->interpolate($heightInputInM)));

        $heightInputInM = 10;
        $expectedAgeOutput = 9;
        $this->assertEquals($expectedAgeOutput, round($regression->interpolate($heightInputInM)));

        $heightInputInM = 30;
        $expectedAgeOutput = 72;
        $this->assertEquals($expectedAgeOutput, round($regression->interpolate($heightInputInM)));

        $heightInputInM = 31;
        $expectedAgeOutput = 80;
        $this->assertEquals($expectedAgeOutput, round($regression->interpolate($heightInputInM)));
    }
}
