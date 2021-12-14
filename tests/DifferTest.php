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
     * @dataProvider diffFormatNestedProvider
     */

    public function testDiffFormatNested($actualNested, $expectedNested): void
    {
        $this->assertEquals($expectedNested, $actualNested);
    }

    public function diffFormatNestedProvider(): array
    {
        $expectedNested = file_get_contents($this->getFilePath('nested.txt'));

        return [
          [genDiff($this->getFilePath('file1.json'), $this->getFilePath('file2.json')), $expectedNested],
          [genDiff($this->getFilePath('file1.yml'), $this->getFilePath('file2.yml')), $expectedNested]
        ];
    }

    /**
     * @dataProvider diffFormatPlainProvider
     */

    public function testDiffFormatPlain($actualPlain, $expectedPlain): void
    {
        $this->assertEquals($expectedPlain, $actualPlain);
    }

    public function diffFormatPlainProvider(): array
    {
        $expectedPlain = file_get_contents($this->getFilePath('plain.txt'));

        return [
            [genDiff($this->getFilePath('file1.json'), $this->getFilePath('file2.json'), 'plain'), $expectedPlain],
            [genDiff($this->getFilePath('file1.yml'), $this->getFilePath('file2.yml'), 'plain'), $expectedPlain]
        ];
    }

    /**
     * @dataProvider diffFormatJsonProvider
     */

    public function testDiffFormatJson($actualJson, $expectedJson): void
    {
        $this->assertEquals($expectedJson, $actualJson);
    }

    public function diffFormatJsonProvider(): array
    {
        $expectedJson = str_replace(["-", "\n"], "", file_get_contents($this->getFilePath('json.txt')));

        return [
            [genDiff($this->getFilePath('file1.json'), $this->getFilePath('file2.json'), 'json'), $expectedJson],
            [genDiff($this->getFilePath('file1.yml'), $this->getFilePath('file2.yml'), 'json'), $expectedJson]
        ];
    }
}
