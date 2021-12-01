<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use function \Differ\Parsers\dataPreparation;
use function \Differ\Stylish\stylish;

class StylishTest extends TestCase
{
    private string $path = __DIR__ . '/fixtures/';
    /**
     * @var false|string[]
     */
    private $expectedNested;

    private function getFilePath($name): string
    {
        return $this->path . $name;
    }
    
    protected function setUp(): void
    {
        $this->expectedNested = file_get_contents($this->getFilePath('nested.txt')) . "\n";
    }
    public function testStylish(): void
    {
        $path1 = __DIR__ . '/' . 'fixtures/file1.json';
        $path2 = __DIR__ . '/' . 'fixtures/file2.json';

        $differ = dataPreparation($path1, $path2);

        $this->assertEquals($this->expectedNested, stylish($differ));
    }
}
