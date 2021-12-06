<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $path = __DIR__ . '/fixtures/';

    private string $expectedNested;
    private string $expectedPlain;
    private string $expectedJson;

    private string $pathToJsonFile1;
    private string $pathToJsonFile2;
    private string $pathToYmlFile1;
    private string $pathToYmlFile2;

    private function getFilePath($name): string
    {
        return $this->path . $name;
    }

    protected function setUp(): void
    {
        $this->pathToJsonFile1 = $this->getFilePath('file1.json');
        $this->pathToJsonFile2 = $this->getFilePath('file2.json');

        $this->pathToYmlFile1 = $this->getFilePath('file1.yml');
        $this->pathToYmlFile2 = $this->getFilePath('file2.yml');

        $this->expectedNested = file_get_contents($this->getFilePath('nested.txt')); // . "\n";
        $this->expectedPlain = file_get_contents($this->getFilePath('plain.txt')); // . "\n";
        $this->expectedJson = file_get_contents($this->getFilePath('json.txt'));
    }

    public function testDiffFormatNested(): void
    {
        $differ1 = genDiff($this->pathToJsonFile1, $this->pathToJsonFile2);

        $this->assertEquals($this->expectedNested, $differ1);

        $differ2 = genDiff($this->pathToYmlFile1, $this->pathToYmlFile2);

        $this->assertEquals($this->expectedNested, $differ2);
    }
    public function testDiffFormatPlain(): void
    {
        $differ1 = genDiff($this->pathToJsonFile1, $this->pathToJsonFile2, 'plain');

        $this->assertEquals($this->expectedPlain, $differ1);

        $differ2 = genDiff($this->pathToYmlFile1, $this->pathToYmlFile2, 'plain');

        $this->assertEquals($this->expectedPlain, $differ2);
    }

    public function testDiffFormatJson(): void
    {
        $expected = str_replace(["-", "\n"], "", $this->expectedJson);

        $differ1 = genDiff($this->pathToJsonFile1, $this->pathToJsonFile2, 'json');

        $this->assertEquals($expected, $differ1);

        $differ2 = genDiff($this->pathToYmlFile1, $this->pathToYmlFile2, 'json');

        $this->assertEquals($expected, $differ2);
    }
}
