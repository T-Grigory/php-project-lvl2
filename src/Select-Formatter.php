<?php

namespace Differ\Select\Formatter;

use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plainFormatter;
use function Differ\Formatters\Json\jsonFormatter;

function selectFormatter(array $diff, string $format): string
{
    return match ($format) {
        'stylish' => stylish($diff),
        'plain' => plainFormatter($diff),
        'json' => jsonFormatter($diff),
        default => throw new \Exception("uknown format: '{$format}'!"),
    };
}
