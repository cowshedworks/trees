<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class TreeCalculator
{
    public static array $requiredvalidParameters = ['circumference', 'age', 'height'];

    protected array $speciesData;

    protected array $treePresentState = [
        'circumference' => null,
        'age'           => null,
        'height'        => null,
    ];

    public function __construct(array $speciesData, array $treeData)
    {
        $this->speciesData = $speciesData;
        $this->build($treeData);
    }

    private function build(array $treeData): void
    {
        // here we should fill the tree present state by calculating
        // the height, age, circumference. We only need one of these
        // values to guess the others using the coefficients
        $this->treePresentState['age'] = 1;
        $this->treePresentState['circumference'] = 1;
        $this->treePresentState['height'] = 1;
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

    public function getAge(): int
    {
        return $this->treePresentState['age'];
    }

    public function getCircumference(): int
    {
        return $this->treePresentState['circumference'];
    }

    public function getHeight(): int
    {
        return $this->treePresentState['height'];
    }

    public static function validateTreeParameters(array $treeParameters): bool
    {
        $totalValidParams = count(self::$requiredvalidParameters);

        $validParamsNotProvided = array_diff(self::$requiredvalidParameters, array_keys($treeParameters));

        return count($validParamsNotProvided) != $totalValidParams;
    }
}
