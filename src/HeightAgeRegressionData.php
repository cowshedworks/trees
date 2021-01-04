<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class HeightAgeRegressionData
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getYearX(): array
    {
        $returnData = [];

        foreach ($this->data as $data) {
            $returnData[] = [
                'x' => $data['year'],
                'y' => $data['value']['value'],
            ];
        }

        return $returnData;
    }

    public function getHeightX(): array
    {
        $returnData = [];

        foreach ($this->data as $data) {
            $returnData[] = [
                'x' => $data['value']['value'],
                'y' => $data['year'],
            ];
        }

        return $returnData;
    }
}