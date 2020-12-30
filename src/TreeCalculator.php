<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class TreeCalculator
{
    protected array $speciesData;

    protected TreeState $treePresentState;

    public function __construct(array $speciesData, array $treeData)
    {
        $this->speciesData = $speciesData;
        $this->treePresentState= new TreeState($treeData);
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
        return $this->treePresentState->getAge();
    }

    public function getCircumference(): int
    {
        return $this->treePresentState->getCircumference();
    }

    public function getHeight(): int
    {
        return $this->treePresentState->getHeight();
    }
}
