<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

interface DataLoaderInterface
{
    public function loadAvailableTrees(): array;

    public function getDataFor(string $species): SpeciesData;
}
