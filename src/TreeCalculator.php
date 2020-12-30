<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class TreeCalculator
{
    protected TreeAttributes $attributes;

    public function __construct(TreeAttributes $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getPopularName(): string
    {
        return $this->attributes->getSpeciesData('name.popular');
    }

    public function getCommonNames(): array
    {
        return $this->attributes->getSpeciesData('name.common');
    }

    public function getScientificName(): array
    {
        return $this->attributes->getSpeciesData('name.scientific');
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
