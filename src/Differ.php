<?php

namespace Differ\Differ;

use function Differ\Formatters\formatData;
use function Differ\DataPreparation\getData;
use function Differ\Parsers\parser;
use function Differ\DiffBuilder\generateAST;

function getAbsolutePath(string $path): string
{
    $result = getcwd();
    if ($result === false) {
        throw new \Exception('Unexpected error!');
    }
    return trim($result) . "/{$path}";
}

function isAbsolutePath(string $path): bool
{
    return $path[0] === '/';
}

function genDiff(string $path1, string $path2, string $format = 'stylish'): string
{
    $pathToFile1 = isAbsolutePath($path1) ? $path1 : getAbsolutePath($path1);
    $pathToFile2 = isAbsolutePath($path2) ? $path2 : getAbsolutePath($path2);

    $data1 = getData($pathToFile1);
    $data2 = getData($pathToFile2);

    $data = [parser($data1['data'], $data1['extension']), parser($data2['data'], $data2['extension'])];

    $diff = generateAST($data[0], $data[1]);

    return formatData($diff, $format);
}
