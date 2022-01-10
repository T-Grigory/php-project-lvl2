<?php

namespace Differ\DataPreparation;

function getData(string $pathToFile): array
{
    if (!file_exists($pathToFile)) {
        throw new \Exception("invalid path to file!");
    }

    $indexExtension = strrpos($pathToFile, '.');

    if ($indexExtension === false) {
        throw new \Exception("No file extension!");
    }

    $extension = substr($pathToFile, $indexExtension + 1);
    $data = file_get_contents($pathToFile);

    if ($data === false) {
        throw new \Exception("Unexpected error!");
    }

    return ['data' => $data, 'extension' => $extension];
}
