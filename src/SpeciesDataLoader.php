<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class SpeciesDataLoader implements DataLoaderInterface
{
    const DATADIR = __DIR__.'/data';

    protected ?string $dataDir = null;

    public function setDataDir(string $dataDir): void
    {
        $this->dataDir = $dataDir;
    }

    public function getDataDir(): string
    {
        return $this->dataDir ?? self::DATADIR;
    }

    public function loadAvailableTrees(): array
    {
        $dataFiles = array_diff(
            scandir($this->getDataDir()),
            ['..', '.']
        );

        $dataFiles = array_map(
            function ($filename) {
                return str_replace('.json', '', $filename);
            },
            $dataFiles
        );

        return array_values($dataFiles);
    }

    public function getDataFor(string $treeName): SpeciesData
    {
        return new SpeciesData(
            json_decode(
                file_get_contents(
                    sprintf(
                        '%s/%s.json',
                        $this->getDataDir(),
                        $treeName
                    )
                ),
                true // JSON OBJECT -> ASSOCIATIVE ARRAY
            )
        );
    }
}
