<?php

namespace Differ\Differ;

use function Differ\Utils\sortObjectProperty;
use function Differ\Utils\objectMergeRecursive;
use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plainFormatter;

function genDiff($pathToFile1, $pathToFile2, $format = 'stylish'): string
{
    $data = \Differ\Parsers\dataPreparation($pathToFile1, $pathToFile2);
    $merged = sortObjectProperty(objectMergeRecursive($data[0], $data[1]));

    $iter = function ($merged, $data1, $data2) use (&$iter) {
        $values = get_object_vars($merged);
        $keys = array_keys($values);

        return array_map(function ($key, $value) use (&$iter, $data1, $data2) {
            $isKeyData1 = property_exists($data1, $key);
            $isKeyData2 = property_exists($data2, $key);

            $value1 = '';
            $value2 = '';

            if ($isKeyData1) {
                $value1 = $data1->$key;
            }

            if ($isKeyData2) {
                $value2 = $data2->$key;
            }

            $isObjectValue1 = isset($value1) && is_object($value1);
            $isObjectValue2 = isset($value2) && is_object($value2);

            if ($isObjectValue1 && $isObjectValue2) {
                return [
                    "name" => $key,
                    "type" => "node",
                    "children" => $iter($value, $value1, $value2)
                ];
            } else {
                $result = [
                    "name"   => $key,
                    "type"   => "leaf"
                ];

                if ($isKeyData1) {
                    $result["before"] = $value1;
                }
                if ($isKeyData2) {
                    $result["after"] = $value2;
                }

                return $result;
            }
        }, $keys, $values);
    };

    $diff = $iter($merged, $data[0], $data[1]);

    switch ($format) {
        case 'stylish':
            return stylish($diff);
        case 'plain':
            return plainFormatter($diff);
        default:
            throw new \Exception("uknown format: '{$format}'!");
    }
}
