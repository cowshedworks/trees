<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Regression;

use DrQue\PolynomialRegression;

class HeightAgeRegression
{
    private HeightAgeRegressionData $data;

    private PolynomialRegression $regression;

    private $interpolateFunction;

    private $dataGetMethod;

    public function __construct(HeightAgeRegressionData $data)
    {
        $this->data = $data;
    }

    public function buildAgeFromHeight(): HeightAgeRegression
    {
        $this->regression = new PolynomialRegression(4);

        $this->dataGetMethod = 'getHeightAsX';

        $this->getData();

        return $this;
    }

    public function buildHeightFromAge(): HeightAgeRegression
    {
        $this->regression = new PolynomialRegression(6);

        $this->dataGetMethod = 'getAgeAsX';

        $this->getData();

        $this->interpolateFunction = fn ($value) => $value * 100;

        return $this;
    }

    private function getData(): void
    {
        foreach ($this->data->{$this->dataGetMethod}() as $regressionData) {
            $this->regression->addData($regressionData['x'], $regressionData['y']);
        }
    }

    private function getInterpolateFunction(): callable
    {
        if ($this->interpolateFunction === null) {
            return fn ($value) => $value;
        }

        return $this->interpolateFunction;
    }

    public function interpolate($x)
    {
        $interpolationCallback = $this->getInterpolateFunction();

        return $interpolationCallback($this->regression->interpolate($this->regression->getCoefficients(), $x));
    }

    public function getRSquared(): float
    {
        $dataForRSquared = [];
        foreach ($this->data->{$this->dataGetMethod}() as $valuePair) {
            $dataForRSquared[] = [$valuePair['x'], $valuePair['y']];
        }

        return $this->regression->RSquared($dataForRSquared, $this->regression->getCoefficients());
    }

    public function getFunctionHtml(): string
    {
        $coef = [];
        $power = 0;
        foreach ($this->regression->getCoefficients() as $coefficient) {
            $coef[] = $coefficient . (($power <= 0) ? ' ' : "x<sup>{$power}</sup>  ");
            $power++;
        }
        $printedFunction = 'f(x) = ' . implode(' + ', $coef);

        return $printedFunction;
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
