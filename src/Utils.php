<?php

namespace Differ\Utils;

function sortObjectProperty(object $data): object
{
    $arr = (array) $data;
    ksort($arr);
    $newData = (object) $arr;

    foreach ($newData as $key => $value) {
        if (is_object($value)) {
            $newData->$key = sortObjectProperty($value);
        }
    }
    return $newData;
}

function copyObjectRecursive(object $data): object
{
    $newData = new class {
    };

    foreach ($data as $key => $value) {
        if (is_object($value)) {
            $newData->$key = copyObjectRecursive($value);
        } else {
            $newData->$key = $value;
        }
    }
    return $newData;
}


function objectMergeRecursive(object $data1, object $data2): object
{
    $copyData1 = copyObjectRecursive($data1);

    $iter = function ($copyData1, $data2) use (&$iter) {
        foreach ($data2 as $key2 => $value2) {
            $value1 = $copyData1->$key2 ?? '';
            if (is_object($value1) && is_object($value2)) {
                $iter($value1, $value2);
            } else {
                $copyData1->$key2 = $value2;
            }
        }
    };
    $iter($copyData1, $data2);

    return $copyData1;
}
