<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

class ConfigLoader
{
    const DATADIR = __DIR__.'/data';

    public function loadAvailableTrees(): array
    {
        $dataFiles = array_diff(
            scandir(self::DATADIR),
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

    public function getConfigFor(string $treeName): array
    {
        return json_decode(
            file_get_contents(
                sprintf(
                    '%s/%s.json',
                    self::DATADIR,
                    $treeName
                )
            ),
            true // JSON OBJECT -> ASSOCIATIVE ARRAY
        );
    }
}
