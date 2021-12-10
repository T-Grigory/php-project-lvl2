<?php

namespace Differ\Differ;

use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plainFormatter;
use function Differ\Formatters\Json\jsonFormatter;
use function Functional\sort;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $data = \Differ\Parsers\dataPreparation($pathToFile1, $pathToFile2);


    $iter = function ($data1, $data2) use (&$iter) {
        $merged = array_merge((array) $data1, (array) $data2);
        $keys = sort(array_keys($merged), fn ($left, $right) => strcmp($left, $right));

        return array_map(function ($key) use (&$iter, $data1, $data2) {
            $isKeyExistsData1 = property_exists($data1, $key);
            $isKeyExistsData2 = property_exists($data2, $key);

            if ($isKeyExistsData1) {
                $value1 = $data1->$key;
            }

            if ($isKeyExistsData2) {
                $value2 = $data2->$key;
            }

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
                        "name"   => $key,
                        "type"   => "removed",
                        "value" => [$value1]
                    ];
                } elseif (!$isKeyExistsData1 && $isKeyExistsData2) {
                    return [
                        "name"   => $key,
                        "type"   => "added",
                        "value" => [$value2]
                    ];
                } elseif ($value1 !== $value2) {
                    return [
                        "name"   => $key,
                        "type"   => "changed",
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

    switch ($format) {
        case 'stylish':
            return stylish($diff);
        case 'plain':
            return plainFormatter($diff);
        case 'json':
            return jsonFormatter($diff);
        default:
            throw new \Exception("uknown format: '{$format}'!");
    }
}
