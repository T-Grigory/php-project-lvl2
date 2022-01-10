<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

const EXTENSIONS_YAML = ['yml', 'yaml'];

function parser(string $data, string $extension): object
{
    return in_array($extension, EXTENSIONS_YAML, true) ?
        Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP) :
        json_decode($data);
}
