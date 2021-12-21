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

    public function testDiff($file1, $file2, $format, $expectedFileName): void
    {
        $actual = genDiff($this->getFilePath($file1), $this->getFilePath($file2), $format);
        $content = file_get_contents($this->getFilePath($expectedFileName));
        $expected = $expectedFileName === 'json.txt' ? str_replace(["-", "\n"], "", $content) : $content;

        $this->assertEquals($expected, $actual);
    }

    public function diffProvider(): array
    {
        return [
            ['file1.json', 'file2.json', 'stylish', 'nested.txt'],
            ['file1.yml', 'file2.yml', 'stylish', 'nested.txt'],
            ['file1.json', 'file2.json', 'plain', 'plain.txt'],
            ['file1.yml', 'file2.yml', 'plain', 'plain.txt'],
            ['file1.json', 'file2.json', 'json', 'json.txt'],
            ['file1.yml', 'file2.yml', 'json', 'json.txt']
        ];
    }
}
