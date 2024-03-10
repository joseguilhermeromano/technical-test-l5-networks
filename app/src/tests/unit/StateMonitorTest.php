<?php

namespace Souce\Tests;

require_once __dir__.'/../../autoload/autoload.php';

use PHPUnit\Framework\TestCase;
use Source\Libs\StateMonitor;
use Exception;
use ReflectionMethod;

class StateMonitorTest extends TestCase
{
    public function testConstructThrowsExceptionWhenFilesNotFound()
    {
        $this->expectException(Exception::class);
        new StateMonitor('/caminho/inexistente/filas', '/caminho/inexistente/ramais');
    }

    public function testGetStateReturnsArray()
    {
        $stateMonitor = new StateMonitor();
        $state = $stateMonitor->getState();
        $this->assertIsArray($state);
    }

    public function testGetStateContainsExpectedKeys()
    {
        $stateMonitor = new StateMonitor();
        $state = $stateMonitor->getState();
        $validate = $state['7000'];
        $this->assertArrayHasKey('name', $validate);
        $this->assertArrayHasKey('extension', $validate);
        $this->assertArrayHasKey('online', $validate);
        $this->assertArrayHasKey('ip_address', $validate);
        $this->assertArrayHasKey('status', $validate);
        $this->assertArrayHasKey('agent', $validate);
    }

    public function testGetStatusAgentsRamaisReturnsArray()
    {
        $stateMonitor = new StateMonitor();
        $method = new ReflectionMethod('Source\Libs\StateMonitor', 'getStatusAgentsRamais');
        $method->setAccessible(true);
        $status = $method->invoke($stateMonitor);
        $this->assertIsArray($status);
    }

    public function testGetInfoRamaisOnReturnsExpectedData()
    {
        $stateMonitor = new StateMonitor();
        $method = new ReflectionMethod('Source\Libs\StateMonitor', 'getInfoRamaisOn');
        $method->setAccessible(true);
        $infoRamais = [];
        $values = ['7000/7000', '181.219.125.7', 'D', 'N', '42367', 'OK'];
        $method->invokeArgs($stateMonitor, [&$infoRamais, $values]);
        $expected = [
            'name' => '7000',
            'extension' => '7000',
            'online' => true,
            'ip_address' => '181.219.125.7',
            'status' => 'busy',
            'agent' => 'Chaves' 
        ];
        $this->assertEquals($expected, $infoRamais['7000']);
    }

    public function testGetInfoRamaisOffReturnsExpectedData()
    {
        $stateMonitor = new StateMonitor();
        $method = new ReflectionMethod('Source\Libs\StateMonitor', 'getInfoRamaisOff');
        $method->setAccessible(true);
        $infoRamais = [];
        $values = ['7004/7002', '(Unspecified)', 'D', 'N', '0', 'UNKNOWN'];
        $method->invokeArgs($stateMonitor, [&$infoRamais, $values]);
        $expected = [
            'name' => '7004',
            'extension' => '7002',
            'online' => false,
            'ip_address' => '(Unspecified)',
            'status' => 'paused',
            'agent' => 'Godines'
        ];
        $this->assertEquals($expected, $infoRamais['7004']);
    }
}
