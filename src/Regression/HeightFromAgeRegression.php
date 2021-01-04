<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\Regression;

use DrQue\PolynomialRegression;

class HeightFromAgeRegression
{
    private array $data;
    
    private PolynomialRegression $regression;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->regression = new PolynomialRegression(3);
    }

    public function interpolate($x)
    {
        foreach ($this->data as $regressionData) {
            $this->regression->addData($regressionData['x'], $regressionData['y']);
        }

        return $this->regression->interpolate($this->regression->getCoefficients(), $x) * 100;
    }
}
