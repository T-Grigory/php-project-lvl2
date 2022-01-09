<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

const EXTENSIONS_YAML = ['yml', 'yaml'];


function dataPreparation(string $pathToFile): array
{
    if (!file_exists($pathToFile)) {
        throw new \Exception("invalid path to file!");
    }

    $indexExtension = strrpos($pathToFile, '.');

    if ($indexExtension === false) {
        throw new \Exception("No file extension!");
    }

    $extension = substr($pathToFile, $indexExtension + 1);
    $data = file_get_contents($pathToFile);

    if ($data === false) {
        throw new \Exception("Unexpected error!");
    }

    return ['data' => $data, 'extension' => $extension];
}

function parser(string $data, string $extension): object
{
    return in_array($extension, EXTENSIONS_YAML, true) ?
        Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP) :
        json_decode($data);
}
