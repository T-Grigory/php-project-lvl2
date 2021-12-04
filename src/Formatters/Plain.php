<?php

namespace Differ\Formatters\Plain;

function getValue($data, $key, $isKeyExists)
{
    return $isKeyExists ? $data[$key] : '';
}

function getNormalizeValue($value)
{
    if (is_object($value)) {
        return '[complex value]';
    } elseif (is_string($value)) {
        return "'{$value}'";
    } else {
        return json_encode($value);
    }
}

function plainFormatter($tree): string
{
    $iter = function ($tree, $acc) use (&$iter) {
        $lines = array_map(function ($node) use ($iter, $acc) {

            $key = $node['name'];
            $property = $acc === '' ? "{$key}" : "{$acc}.{$key}";


            if ($node['type'] === 'node') {
                return $iter($node['children'], $property);
            } else {
                $template = "Property '{$property}' was";

                $isKeyBefore = array_key_exists('before', $node);
                $isKeyAfter = array_key_exists('after', $node);

                $value1 = getValue($node, 'before', $isKeyBefore);
                $value2 = getValue($node, 'after', $isKeyAfter);

                $updatedValue1 = getNormalizeValue($value1);
                $updatedValue2 = getNormalizeValue($value2);

                if (!$isKeyBefore && $isKeyAfter) {
                    return "{$template} added with value: {$updatedValue2}";
                } elseif ($isKeyBefore && !$isKeyAfter) {
                    return "{$template} removed";
                } elseif ($updatedValue1 !== $updatedValue2) {
                    return "{$template} updated. From {$updatedValue1} to {$updatedValue2}";
                } else {
                    return '';
                }
            }
        },
        $tree);

        $filtered = array_filter($lines, fn($line) => $line !== '');
        return implode("\n", $filtered);
    };

    return $iter($tree, '') . "\n";
}
