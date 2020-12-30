<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class TreeCalculator
{
    public static array $requiredvalidParameters = ['circumference', 'age', 'height'];

    protected array $speciesData;

    public function __construct(array $speciesData, array $treeData)
    {
        $this->speciesData = $speciesData;
    }

    public static function validateTreeParameters(array $treeParameters): bool
    {
        $totalValidParams = count(self::$requiredvalidParameters);

        $validParamsNotProvided = array_diff(self::$requiredvalidParameters, array_keys($treeParameters));

        return count($validParamsNotProvided) != $totalValidParams;
    }

    public function getPopularName(): string
    {
        return $this->speciesData['name']['popular'];
    }

    public function getCommonNames(): array
    {
        return $this->speciesData['name']['common'];
    }

    public function getScientificName(): array
    {
        return $this->speciesData['name']['scientific'];
    }
}
