<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

use Exception;

class SpeciesData
{
    private array $speciesData;

    public function __construct(array $rawData)
    {
        $this->speciesData = $rawData;
    }

    public function get(string $dataKey)
    {
        try {
            $currentValue = $this->speciesData;

            foreach (explode('.', $dataKey) as $key) {
                if (isset($currentValue[$key]) === false) {
                    throw new Exception('Species data key does not exist');
                }
                $currentValue = $currentValue[$key];
            }

            return $currentValue;
        } catch (Exception $exception) {
            return null;
        }
    }
}
