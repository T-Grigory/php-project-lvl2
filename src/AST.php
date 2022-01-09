<?php

namespace Differ\AST;

use function Functional\sort;

function generateAST($data1, $data2): array
{
    $updatedData1 = (array) $data1;
    $updatedData2 = (array) $data2;
    $merged = array_merge($updatedData1, $updatedData2);
    $keys = sort(array_keys($merged), fn ($left, $right) => strcmp($left, $right));

    return array_map(function ($key) use ($updatedData1, $updatedData2) {
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
                "children" => generateAST($value1, $value2)
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
}
