<?php

require_once __dir__.'/../../autoload/autoload.php';

use PHPUnit\Framework\TestCase;
use Source\Libs\ExtensionsGenerator;

class ExtensionsGeneratorTest extends TestCase
{
    public function testGenerateCreatesFileWithCorrectFormat()
    {
        $fileName = __dir__.'/../../libs/test_ramais';
        $monitoredOnline = 3;
        $monitoredOffline = 2;

        $generator = new ExtensionsGenerator($fileName, $monitoredOnline, $monitoredOffline);
        $generatedFileName = $generator->generate();

        $this->assertFileExists($generatedFileName);

        $content = file_get_contents($generatedFileName);

        $this->assertStringContainsString(
            'Name/username              Host            Dyn Nat ACL Port     Status', 
            $content
        );

        /** coloquei +3 para desconsiderar cabeçalho, rodapé e linha final */
        $this->assertCount($monitoredOnline + $monitoredOffline + 3, explode(PHP_EOL, $content));

        unlink($generatedFileName);
    }

    public function testGenerateReturnsCorrectFilePath()
    {
        $fileName = __dir__.'/../../libs/test_ramais';
        $monitoredOnline = 3;
        $monitoredOffline = 2;

        $generator = new ExtensionsGenerator($fileName, $monitoredOnline, $monitoredOffline);
        $generatedFileName = $generator->generate();

        $expectedFileName = dirname($fileName) . '/new_' . basename($fileName);
        
        $this->assertEquals($expectedFileName, $generatedFileName);

        unlink($generatedFileName);
    }
}