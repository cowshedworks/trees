<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class TreeCalculator
{
    protected array $speciesData;

    protected TreeAttributes $attributes;

    public function __construct(array $speciesData, array $treeData)
    {
        $this->speciesData = $speciesData;
        $this->attributes = new TreeAttributes($treeData);
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
        return $this->attributes->getAge();
    }

    public function getCircumference(): int
    {
        return $this->attributes->getCircumference();
    }

    public function getHeight(): int
    {
        return $this->attributes->getHeight();
    }
}
