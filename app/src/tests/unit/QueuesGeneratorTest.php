<?php

require_once __dir__.'/../../autoload/autoload.php';

use PHPUnit\Framework\TestCase;
use Source\Libs\QueuesGenerator;

class QueuesGeneratorTest extends TestCase
{
    public function testGenerateCreatesFileWithCorrectFormat()
    {
        $fileName = 'test.txt';
        $maxCalls = 20;
        $maxAgents = 5;

        $generator = new QueuesGenerator($fileName, $maxCalls, $maxAgents);
        $generatedFileName = $generator->generate();

        $this->assertFileExists($generatedFileName);

        $content = file_get_contents($generatedFileName);

        $string = 'gcallcenter-SUPORTE              has 0 calls (max ';
        $string .=  $maxCalls . ') in \'ringall\' strategy (1s holdtime), W:0, C:';
        $string .= $maxAgents . ', A:0, SL:0.0% within 0s';

        $this->assertStringContainsString(
            $string, 
            $content
        );

        $this->assertCount(9, explode(PHP_EOL, $content));

        unlink($generatedFileName);
    }

    public function testGenerateReturnsCorrectFilePath()
    {
        $fileName = 'test.txt';
        $maxCalls = 20;
        $maxAgents = 5;

        $generator = new QueuesGenerator($fileName, $maxCalls, $maxAgents);
        $generatedFileName = $generator->generate();

        $expectedFileName = dirname($fileName) . '/new_' . basename($fileName);
        
        $this->assertEquals($expectedFileName, $generatedFileName);

        unlink($generatedFileName);
    }
}