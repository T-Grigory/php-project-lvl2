<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Utils\genDiff;

class UtilsTest extends TestCase
{

    public function testGenDiff(): void
    {
        $data1 = [
            'host'    => "hexlet.io",
            'timeout' => 50,
            'proxy'   => "123.234.53.22",
            'follow'  => false
        ];

        $data2 = [
            'timeout' => 20,
            'verbose' => true,
            'host' => "hexlet.io"
        ];

        $rightData = [
            '- follow: false',
            '  host: hexlet.io',
            '- proxy: 123.234.53.22',
            '- timeout: 50',
            '+ timeout: 20',
            '+ verbose: true'
        ];

        $right1 = implode("\n", $rightData) . "\n";

        $this->assertEquals($right1, genDiff($data1, $data2));
    }
}
