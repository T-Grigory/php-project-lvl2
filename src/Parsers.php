<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

const EXTENSIONS_YAML = ['yml', 'yaml'];

function parser(string $pathToFile1, string $pathToFile2): string
{
    if (!file_exists($pathToFile1) || !file_exists($pathToFile2)) {
        throw new \Exception("invalid path to file!");
    }

    $extension1 = substr($pathToFile1, strrpos($pathToFile1, '.') + 1);
    $extension2 = substr($pathToFile1, strrpos($pathToFile1, '.') + 1);

    $data1 = [];
    $data2 = [];

    if (in_array($extension1, EXTENSIONS_YAML) || in_array($extension2, EXTENSIONS_YAML)) {
        $data1 = Yaml::parseFile($pathToFile1);
        $data2 = Yaml::parseFile($pathToFile2);
    } elseif ($extension1 === 'json' || $extension2 === 'json') {
        $data1 = json_decode(file_get_contents($pathToFile1), true);
        $data2 = json_decode(file_get_contents($pathToFile2), true);
    }

    return \Differ\Utils\genDiff($data1, $data2);
}
