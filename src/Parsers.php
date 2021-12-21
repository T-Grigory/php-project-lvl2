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
    $indexExtension = strrpos($pathToFile, '.');

    if ($indexExtension === false) {
        throw new \Exception("No file extension!");
    }
    $extension = substr($pathToFile, $indexExtension + 1);
    $content = file_get_contents($pathToFile);

    if ($content === false) {
        throw new \Exception("Unexpected error!");
    }

    return in_array($extension, EXTENSIONS_YAML, true) ?
        Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP) :
        json_decode($content);
}
