<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Regression;

use DrQue\PolynomialRegression;

class HeightAgeRegression
{
    private HeightAgeRegressionData $data;

    private PolynomialRegression $regression;

    private $interpolateFunction;

    public function __construct(HeightAgeRegressionData $data)
    {
        $this->data = $data;
    }

    public function buildAgeFromHeight(): HeightAgeRegression
    {
        $this->regression = new PolynomialRegression(4);

        foreach ($this->data->getHeightAsX() as $regressionData) {
            $this->regression->addData($regressionData['x'], $regressionData['y']);
        }

        return $this;
    }

    public function buildHeightFromAge(): HeightAgeRegression
    {
        $this->regression = new PolynomialRegression(3);

        foreach ($this->data->getAgeAsX() as $regressionData) {
            $this->regression->addData($regressionData['x'], $regressionData['y']);
        }

        $this->interpolateFunction = fn($value) => $value * 100;

        return $this;
    }

    private function getInterpolateFunction(): Callable
    {
        if ($this->interpolateFunction === null) {
            return fn($value) => $value;
        }

        return $this->interpolateFunction;
    }

    public function interpolate($x)
    {
        $interpolationCallback = $this->getInterpolateFunction();

        return $interpolationCallback($this->regression->interpolate($this->regression->getCoefficients(), $x));
    }

    public function getRegressionLine($toX): array
    {
        $returnData = [];

        for ($x = 0; $x <= $toX; $x++) {
            $returnData[$x] = $this->interpolate($x);
        }

        return $returnData;
    }
}
