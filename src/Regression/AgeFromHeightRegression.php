<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Regression;

use DrQue\PolynomialRegression;

class AgeFromHeightRegression
{
    private array $data;

    private PolynomialRegression $regression;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->regression = new PolynomialRegression(4);

        foreach ($this->data as $regressionData) {
            $this->regression->addData($regressionData['x'], $regressionData['y']);
        }
    }

    public function interpolate($x)
    {
        return $this->regression->interpolate($this->regression->getCoefficients(), $x);
    }
}
