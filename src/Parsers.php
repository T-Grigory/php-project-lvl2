<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function getDataParsing(string $data, string $extension): object
{
    $extension = $extension === 'yaml' ? 'yml' : $extension;

    return match ($extension) {
        'yml' => Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP),
        'json' => json_decode($data),
        default => throw new \Exception("uknown extension: '{$extension}'!"),
    };
}
