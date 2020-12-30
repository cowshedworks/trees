<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class TreeState
{
    public static array $requiredvalidParameters = ['circumference', 'age', 'height'];

    private int $age;
    private int $circumference;
    private int $height;

    public function __construct(array $treeData)
    {
        $this->resolveFromData($treeData);
    }

    private function resolveFromData(array $treeData): void
    {
        $this->age = 1;
        $this->circumference = 1;
        $this->height = 1;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getCircumference(): int
    {
        return $this->circumference;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public static function validateTreeParameters(array $treeParameters): bool
    {
        $totalValidParams = count(self::$requiredvalidParameters);

        $validParamsNotProvided = array_diff(self::$requiredvalidParameters, array_keys($treeParameters));

        return count($validParamsNotProvided) != $totalValidParams;
    }
}
