<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Parsers\parser;

class ParsersTest extends TestCase
{
    public function testParserYaml(): void
    {
        $data1 = [
            '+ description: hexlet - practical programming courses.',
            '- follow: false',
            '- host: hexlet.io',
            '+ host: hexlet.ru',
            '- proxy: 123.234.53.22',
            '- timeout: 50',
            '+ timeout: 20',
            '+ verbose: true'
        ];

        $path1 = __DIR__ . '/' . 'fixtures/file1.yml';
        $path2 = __DIR__ . '/' . 'fixtures/file2.yml';

        $right1 = implode("\n", $data1) . "\n";

        $this->assertEquals($right1, parser($path1, $path2));
    }

    public function testParserJson(): void
    {
        $data1 = [
            '- follow: false',
            '  host: hexlet.io',
            '- proxy: 123.234.53.22',
            '- timeout: 50',
            '+ timeout: 20',
            '+ verbose: true'
        ];

        $path1 = __DIR__ . '/' . 'fixtures/file1.json';
        $path2 = __DIR__ . '/' . 'fixtures/file2.json';

        $right1 = implode("\n", $data1) . "\n";

        $this->assertEquals($right1, parser($path1, $path2));


        $data2 = [
            '- follow: false',
            '  host: hexlet.io',
            '+ ip_address: 182.222.10.84',
            '- proxy: 123.234.53.22',
            '- timeout: 50',
            '+ timeout: 5',
            '+ verbose: true'
        ];

        $path3 = __DIR__ . '/' . 'fixtures/file3.json';

        $right2 = implode("\n", $data2) . "\n";

        $this->assertEquals($right2, parser($path1, $path3));
    }
}