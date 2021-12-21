<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private function getFilePath($name): string
    {
        return __DIR__ . '/fixtures/' . $name;
    }

    /**
     * @dataProvider diffProvider
     */

    public function testDiff($file1, $file2, $format, $expected): void
    {
        $actual = genDiff($this->getFilePath($file1), $this->getFilePath($file2), $format);
        $this->assertEquals($expected, $actual);
    }

    public function diffProvider(): array
    {
        $expectedNested = file_get_contents($this->getFilePath('nested.txt'));
        $expectedPlain = file_get_contents($this->getFilePath('plain.txt'));
        $expectedJson = str_replace(["-", "\n"], "", file_get_contents($this->getFilePath('json.txt')));

        return [
            ['file1.json', 'file2.json', 'stylish', $expectedNested],
            ['file1.yml', 'file2.yml', 'stylish', $expectedNested],
            ['file1.json', 'file2.json', 'plain', $expectedPlain],
            ['file1.yml', 'file2.yml', 'plain', $expectedPlain],
            ['file1.json', 'file2.json', 'json', $expectedJson],
            ['file1.yml', 'file2.yml', 'json', $expectedJson]
        ];
    }
}
