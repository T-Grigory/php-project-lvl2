<?php

namespace Differ\Formatters\Json;

function getValue($data, $key, $isKeyExists)
{
    return $isKeyExists ? $data[$key] : '';
}

function jsonFormatter($tree): string
{
    $iter = function ($tree) use (&$iter) {
        $lines = array_map(function ($item) use ($iter) {
            $key = $item['name'];

            if ($item['type'] === 'node') {
                return "\"{$key}\":{$iter($item['children'])}";
            } else {
                $isKeyBefore = array_key_exists('before', $item);
                $isKeyAfter = array_key_exists('after', $item);

                $value1 = getValue($item, 'before', $isKeyBefore);
                $value2 = getValue($item, 'after', $isKeyAfter);

                $updatedValue1 = json_encode($value1);
                $updatedValue2 = json_encode($value2);

                if (!$isKeyBefore && $isKeyAfter) {
                    return "\"{$key}\":{\"after\":{$updatedValue2}}";
                } elseif ($isKeyBefore && !$isKeyAfter) {
                    return "\"{$key}\":{\"before\":{$updatedValue1}}";
                } else {
                    return "\"$key\":{\"before\":{$updatedValue1},\"after\":{$updatedValue2}}";
                }
            }
        },
        $tree);

        return "{" . implode(',', $lines) . "}";
    };

    return $iter($tree);
}
