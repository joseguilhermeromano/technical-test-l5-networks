<?php

namespace Source\Libs;

class ExtensionsGenerator{

    private $fileName;
    private $extensions;
    private $onlineCount;
    private $offlineCount;
    

    public function __construct(
        $fileName,
        $monitoredOnline = 3, 
        $monitoredOffline = 2, 
    ) {
        $this->fileName = $fileName;
        $this->extensions = array();
        $this->onlineCount = $monitoredOnline;
        $this->offlineCount = $monitoredOffline;

        $this->generateExtensions(
            $monitoredOnline, 
            $monitoredOffline, 
        );
    }

    private function generateExtensions(
            $monitoredOnline, 
            $monitoredOffline, 
        ) {
        $ipAddress = '181.219.125.7';
        $status = array('OK', 'UNKNOWN');
        $ports = array(42367, 42368, 42369, 42370, 42371);
        $msValues = array(33, 20, 15);

        // Monitored online peers
        for ($i = 0; $i < $monitoredOnline; $i++) {
            $statusGenerated = $status[array_rand($status)];
            $ip = ($statusGenerated == 'UNKNOWN') ? '(Unspecified)' : $ipAddress;
            $extension = '700' . $i;
            $this->extensions[] = array(
                'extension' => $extension,
                'ip' => $ip,
                'status' => $statusGenerated,
                'port' => ($statusGenerated == 'OK') ? $ports[$i] : '-',
                'ms' => ($statusGenerated == 'OK') ? ' ('.$msValues[array_rand($msValues)].' ms)' : ''
            );
        }

        // Monitored offline peers
        for ($i = $monitoredOnline; $i < $monitoredOnline + $monitoredOffline; $i++) {
            $statusGenerated = $status[array_rand($status)];
            $ip = ($statusGenerated == 'UNKNOWN') ? '(Unspecified)' : $ipAddress;
            $extension = '700' . $i;
            $this->extensions[] = array(
                'extension' => $extension,
                'ip' => $ip,
                'status' => $statusGenerated,
                'port' => ($statusGenerated == 'OK') ? $ports[$i] : '-',
                'ms' => ($statusGenerated == 'OK') ? ' ('.$msValues[array_rand($msValues)].' ms)' : ''
            );
        }
    }

    public function generate() {
        
        $fileName = dirname($this->fileName) . '/new_' . basename($this->fileName);
        $file = fopen($fileName, 'w');
        $cabecalho = 'Name/username              Host            Dyn Nat ACL Port     Status';
        fwrite($file, $cabecalho . PHP_EOL);

        foreach ($this->extensions as $extension) {
            $line = $extension['extension'] . '/' . $extension['extension'];
            $line .= str_repeat(' ', 22 - strlen($extension['extension'])) . $extension['ip'];
            $line .= str_repeat(' ', 16 - strlen($extension['ip'])) . 'D   N       ' . $extension['port'];
            $line .= str_repeat(' ', 6 - strlen($extension['port'])) . '   ' . $extension['status'];
            $line .=  $extension['ms'];
            fwrite($file, $line . PHP_EOL);
        }
        $line = count($this->extensions) . ' sip peers [Monitored: ' . $this->onlineCount . ' online, ';
        $line .= $this->offlineCount . ' offline Unmonitored: 0 online, 0 offline]';
        fwrite($file, $line . PHP_EOL);
        fclose($file);

        return $fileName;
    }
}