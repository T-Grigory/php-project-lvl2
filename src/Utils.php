<?php

namespace Differ\Utils;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    var_dump($pathToFile1);
    if (!file_exists($pathToFile1) || !file_exists($pathToFile2)) {
        throw new \Exception("invalid path to file!");
    }


    $file1 = file_get_contents($pathToFile1);
    $file2 = file_get_contents($pathToFile2);

    $data1 = json_decode($file1, true);
    $data2 = json_decode($file2, true);

    $dataMerge = array_merge($data1, $data2);

    ksort($dataMerge);

    $result = [];

    foreach ($dataMerge as $key => $value) {
        $normalizeValue = json_encode($value);

        $template = "{$key}: {$normalizeValue}";

        if (!array_key_exists($key, $data1)) {
            $result[] = "+ {$template}";
        } elseif (!array_key_exists($key, $data2)) {
            $result[] = "- {$template}";
        } elseif ($value !== $data1[$key]) {
            $result[] = "- {$key}: {$data1[$key]}";
            $result[] = "+ {$template}";
        } else {
            $result[] = "  {$template}";
        }
    }

    return implode("\n", $result) . "\n";
}
