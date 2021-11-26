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
        $data = $factory->build('testTree', [
            'height'        => '10cm',
            'circumference' => '5cm',
        ]);

        $regression = $data
            ->getHeightAgeRegression()
            ->buildAgeFromHeight();

        $heightInputInM = 0.5;
        $expectedAgeOutput = 0;
        $this->assertEquals($expectedAgeOutput, round($regression->interpolate($heightInputInM)));

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

    /**
     * @test
     */
    public function height_from_age_regression(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree', [
            'height'        => '10cm',
            'circumference' => '5cm',
        ]);

        $regression = $data
            ->getHeightAgeRegression()
            ->buildHeightFromAge();

        $ageInputInYears = 0;
        $expectedHeightOutputInCm = 19;
        $this->assertEquals($expectedHeightOutputInCm, round($regression->interpolate($ageInputInYears)));

        $ageInputInYears = 1;
        $expectedHeightOutputInCm = 190;
        $this->assertEquals($expectedHeightOutputInCm, round($regression->interpolate($ageInputInYears)));

        $ageInputInYears = 5;
        $expectedHeightOutputInCm = 764;
        $this->assertEquals($expectedHeightOutputInCm, round($regression->interpolate($ageInputInYears)));

        $ageInputInYears = 10;
        $expectedHeightOutputInCm = 1287;
        $this->assertEquals($expectedHeightOutputInCm, round($regression->interpolate($ageInputInYears)));

        $ageInputInYears = 60;
        $expectedHeightOutputInCm = 2944;
        $this->assertEquals($expectedHeightOutputInCm, round($regression->interpolate($ageInputInYears)));

        $ageInputInYears = 80;
        $expectedHeightOutputInCm = 3123;
        $this->assertEquals($expectedHeightOutputInCm, round($regression->interpolate($ageInputInYears)));
    }

    /** @test */
    public function a_regression_can_return_r_squared(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree', [
            'height'        => '10cm',
            'circumference' => '5cm',
        ]);

        $regression = $data
            ->getHeightAgeRegression()
            ->buildAgeFromHeight();

        $this->assertEquals(0.98748325840844, $regression->getRSquared());
    }

    /** @test */
    public function a_regression_can_return_the_function_html(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree', [
            'height'        => '10cm',
            'circumference' => '5cm',
        ]);

        $regression = $data
            ->getHeightAgeRegression()
            ->buildAgeFromHeight();

        $this->assertNotNull($regression->getFunctionHtml());
    }

    /** @test */
    public function a_regression_can_return_the_regression_line_curve(): void
    {
        $factory = $this->getTreeDataFactory();
        $data = $factory->build('testTree', [
            'height'        => '10cm',
            'circumference' => '5cm',
        ]);

        $regression = $data
            ->getHeightAgeRegression()
            ->buildAgeFromHeight();

        $this->assertCount(10, $regression->getRegressionLine($upToIncludingIndex = 9));
    }
}
