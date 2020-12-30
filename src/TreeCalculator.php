<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class TreeCalculator
{
    protected array $treeData;

    public function __construct(array $treeData)
    {
        $this->treeData = $treeData;
    }

    public function getPopularName(): string
    {
        return $this->treeData['name']['popular'];
    }

    public function getCommonNames(): array
    {
        return $this->treeData['name']['common'];
    }

    public function getScientificName(): array
    {
        return $this->treeData['name']['scientific'];
    }
}