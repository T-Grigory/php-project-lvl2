<?php

namespace Differ\Utils;

function genDiff(array $data1, array $data2): string
{
    $dataMerge = array_merge($data1, $data2);

    ksort($dataMerge);

    $result = [];

    foreach ($dataMerge as $key => $value) {
        $normalizeValue = is_bool($value) ? json_encode($value) : $value;

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
