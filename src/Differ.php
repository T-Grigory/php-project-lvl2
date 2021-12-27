<?php

namespace Differ\Differ;

use function Functional\sort;
use function Differ\Select\Formatter\selectFormatter;

function getAbsolutePath(string $path): string
{
    exec('pwd', $dir, $resultCode);
    if ($resultCode !== 0) {
        throw new \Exception('Unexpected error!');
    }

    return trim($dir[0]) . "/{$path}";
}

function isAbsolutePath(string $path): bool
{
    return $path[0] === '/';
}

function genDiff(string $path1, string $path2, string $format = 'stylish'): string
{
    $pathToFile1 = isAbsolutePath($path1) ? $path1 : getAbsolutePath($path1);
    $pathToFile2 = isAbsolutePath($path2) ? $path2 : getAbsolutePath($path2);

    $data = \Differ\Parsers\dataPreparation($pathToFile1, $pathToFile2);

    $iter = function ($data1, $data2) use (&$iter) {
        $updatedData1 = (array) $data1;
        $updatedData2 = (array) $data2;
        $merged = array_merge($updatedData1, $updatedData2);
        $keys = sort(array_keys($merged), fn ($left, $right) => strcmp($left, $right));

        return array_map(function ($key) use (&$iter, $updatedData1, $updatedData2) {
            $isKeyExistsData1 = array_key_exists($key, $updatedData1);
            $isKeyExistsData2 = array_key_exists($key, $updatedData2);

            $value1 = $isKeyExistsData1 ? $updatedData1[$key] : '';
            $value2 = $isKeyExistsData2 ? $updatedData2[$key] : '';

            $isObjectValue1 = $isKeyExistsData1 && is_object($value1);
            $isObjectValue2 = $isKeyExistsData2 && is_object($value2);

            if ($isObjectValue1 && $isObjectValue2) {
                return [
                    "name" => $key,
                    "type" => "node",
                    "children" => $iter($value1, $value2)
                ];
            } else {
                if ($isKeyExistsData1 && !$isKeyExistsData2) {
                    return [
                        "name" => $key,
                        "type" => "removed",
                        "value" => [$value1]
                    ];
                } elseif (!$isKeyExistsData1 && $isKeyExistsData2) {
                    return [
                        "name" => $key,
                        "type" => "added",
                        "value" => [$value2]
                    ];
                } elseif ($value1 !== $value2) {
                    return [
                        "name" => $key,
                        "type" => "changed",
                        "value" => [$value1, $value2]
                    ];
                } else {
                    return [
                        "name" => $key,
                        "type" => "unchanged",
                        "value" => [$value1]
                    ];
                }
            }
        }, $keys);
    };

    $diff = $iter($data[0], $data[1]);

    return selectFormatter($diff, $format);
}
