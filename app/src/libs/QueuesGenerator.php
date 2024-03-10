<?php 

namespace Source\Libs;

class QueuesGenerator{

    private $fileName;
    private $maxCalls;
    private $maxAgents;
    private $agents;
    
    public function __construct($fileName, $maxCalls = 20, $maxAgents = 5) {
        $this->fileName = $fileName;
        $this->maxCalls = $maxCalls;
        $this->maxAgents = $maxAgents;
        $this->agents = array();
        $this->generateAgents();
    }

    private function generateAgents() {

        $status = array('(In use)', '(Ring)', '(Unavailable)', '(paused)', '');
        $names = array('Chaves', 'Kiko', 'Chiquinha', 'Nhonho', 'Godines');
        shuffle($names);

        for ($i = 0; $i < $this->maxAgents; $i++) {
            $agent = array(
                'extension' => '700' . $i,
                'penalty' => rand(1, 5),
                'status' => $status[array_rand($status)],
                'calls_taken' => rand(0, $this->maxCalls),
                'last_call_secs_ago' => rand(0, 20000),
                'name' => $names[$i]
            );
            $this->agents[] = $agent;
        }
    }

    public function generate() {

        $fileName = dirname($this->fileName) . '/new_' . basename($this->fileName);
        $file = fopen($fileName, 'w');
        $line = 'gcallcenter-SUPORTE              has 0 calls (max ';
        $line .= $this->maxCalls . ') in \'ringall\' strategy (1s holdtime), W:0, C:';
        $line .= count($this->agents) . ', A:0, SL:0.0% within 0s';

        fwrite($file, $line . PHP_EOL);
        fwrite($file, '   Members:' . PHP_EOL);

        foreach ($this->agents as $agent) {
            $line = '      SIP/' . $agent['extension'] . ' with penalty ' . $agent['penalty'];
            $line .= ' (dynamic) ' . $agent['status'] . ' ';
            fwrite($file, $line);
            if ($agent['status'] == 'In use' || $agent['status'] == 'Unavailable') {
                $line = 'has taken ' . $agent['calls_taken'] . ' calls (last was ';
                $line .= $agent['last_call_secs_ago'] . ' secs ago) ' . $agent['name'];
                fwrite($file,  $line . PHP_EOL);
            } else {
                fwrite($file, '(Not in use) has taken no calls yet ' . $agent['name'] . PHP_EOL);
            }
        }

        fwrite($file, '   No Callers' . PHP_EOL);
        fclose($file);

        return $fileName;
    }

}