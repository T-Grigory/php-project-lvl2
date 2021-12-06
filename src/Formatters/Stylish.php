<?php

namespace Differ\Formatters\Stylish;

function stylish($tree, $replacer = ' ', $spaceCount = 4, $startIndentSize = 2): string
{

    $iter = function ($tree, $indentSize, $innerIter) use (&$iter, $replacer, $spaceCount) {

        if (!is_array($tree)) {
            return trim(json_encode($tree), "\"");
        }

        $currentIndent = str_repeat($replacer, $indentSize);
        $bracketIndent = str_repeat($replacer, $indentSize - $spaceCount / 2);
        $indentSize += $spaceCount;

        if ($innerIter) {
            $data = array_map(
                function ($key, $value) use (&$iter, $currentIndent, $indentSize, $innerIter) {
                    $normalizeValue = is_object($value) ? (array) $value : $value;
                    return "{$currentIndent}  {$key}: {$iter($normalizeValue, $indentSize, $indentSize)}";
                },
                array_keys($tree),
                $tree
            );
        } else {
            $data = array_map(
                function ($node) use (
                    $iter,
                    $spaceCount,
                    $replacer,
                    $currentIndent,
                    $indentSize,
                    $innerIter
                ) {

                    $key = $node['name'];

                    if ($node['type'] === 'node') {
                        return "{$currentIndent}  {$key}: {$iter($node['children'], $indentSize, $innerIter)}";
                    } else {
                        $innerIter = true;

                        $isKeyBefore = array_key_exists('before', $node);
                        $isKeyAfter = array_key_exists('after', $node);

                        $before = '';
                        $after = '';

                        if ($isKeyBefore) {
                            $normalizeBefore = is_object($node['before']) ? (array) $node['before'] : $node['before'];
                            $before = $iter($normalizeBefore, $indentSize, $innerIter);
                        }

                        if ($isKeyAfter) {
                            $normalizeAfter = is_object($node['after']) ? (array) $node['after'] : $node['after'];
                            $after = $iter($normalizeAfter, $indentSize, $innerIter);
                        }

                        if (!$isKeyBefore && $isKeyAfter) {
                            return "{$currentIndent}+ {$key}: {$after}";
                        } elseif ($isKeyBefore && !$isKeyAfter) {
                            return "{$currentIndent}- {$key}: {$before}";
                        } elseif ($before !== $after) {
                            return "{$currentIndent}- {$key}: {$before}\n{$currentIndent}+ {$key}: {$after}";
                        } else {
                            return "{$currentIndent}  {$key}: {$before}";
                        }
                    }
                },
                $tree
            );
        }

        $result = ["{", ...$data, "{$bracketIndent}}"];

        return implode("\n", $result);
    };

    return $iter($tree, $startIndentSize, false); //. "\n";
}
