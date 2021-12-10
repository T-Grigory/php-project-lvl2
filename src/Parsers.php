<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

const EXTENSIONS_YAML = ['yml', 'yaml'];


function dataPreparation(string $pathToFile1, string $pathToFile2): array
{

    if (!file_exists($pathToFile1) || !file_exists($pathToFile2)) {
        throw new \Exception("invalid path to file!");
    }

    $data1 = parser($pathToFile1);
    $data2 = parser($pathToFile2);

    return [$data1, $data2];
}

function parser(string $pathToFile): object
{
    $extension = substr($pathToFile, (strrpos($pathToFile, '.') ?? 0) + 1);
    $data = file_get_contents($pathToFile) ?? '';

    return in_array($extension, EXTENSIONS_YAML, true) ?
        Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP) :
        json_decode($data);
}
