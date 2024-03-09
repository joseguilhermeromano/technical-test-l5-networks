<?php

namespace Source\Libs;
use Exception;

class StateMonitor{
    private $ramais;
    private $filas;

    public function __construct()
    {
        $pathFileRamais = __DIR__.'/ramais';
        $pathFileFilas = __DIR__.'/filas';

        if (!file_exists($pathFileRamais)) {
            throw new Exception("File 'ramais' not found");
        }

        $this->ramais = file($pathFileRamais);

        if (!file_exists($pathFileFilas)) {
            throw new Exception("File 'filas' not found");
        }

        $this->filas = file($pathFileFilas);
    }

    protected function getStatusAgentsRamais()
    {
        $data = [];

        foreach ($this->filas as $linhas) {

            if (strstr($linhas, 'SIP/')) {
                $linha = explode(' ', trim($linhas));
                list(, $ramal) = explode('/', $linha[0]);

                $data[$ramal] = ['agent' => end($linha)];

                if (strstr($linhas, '(Ring)')) {
                    $data[$ramal]['status'] = 'calling';
                } elseif (strstr($linhas, '(In use)')) {
                    $data[$ramal]['status'] = 'busy';
                } elseif(strstr($linhas, '(paused)')){
                    $data[$ramal]['status'] = 'paused';
                }elseif (strstr($linhas, '(Not in use)')) {
                    $data[$ramal]['status'] = 'available';
                } elseif (strstr($linhas, '(Unavailable)')) {
                    $data[$ramal]['status'] = 'unavailable';
                }
            }
        }

        return $data;
    }

    public function generate(): array
    {
        $response = [];

        foreach ($this->ramais as $linhas) {

            $linha = array_filter(explode(' ', $linhas), function ($value) {
                return $value !== '' && $value !== null;
            });

            $values = array_values($linha);

            $this->getInfoRamaisOn($response, $values);
            $this->getInfoRamaisOff($response, $values);
        }

        return $response;
    }

    protected function getInfoRamaisOn(&$infoRamais, $values)
    {
        $fieldStatusIsOk = trim($values[5]) == "OK"; 

        $data =  $this->getStatusAgentsRamais();

        if(empty($data)) {
            throw new Exception("Cannot get status of 'ramais'");
        }

        if($fieldStatusIsOk){
            list($name, $username) = explode('/', $values[0]);
            $infoRamais[$name] = array(
                'name' => $name,
                'extension' => $username,
                'online' => true,
                'status' => $data[$name]['status'],
                'agent' => $data[$name]['agent']
            );

            return $infoRamais;
        }

        return $infoRamais;
    }

    protected function getInfoRamaisOff(&$infoRamais, $values)
    {
        $fieldStatus = trim(end($values)) == 'UNKNOWN';
        $fieldHost = trim($values[1]) == '(Unspecified)';
        $data = $this->getStatusAgentsRamais();

        if(empty($data)) {
            throw new Exception("Cannot get status of 'ramais'");
        }

        if($fieldHost && $fieldStatus){
            list($name, $username) = explode('/', $values[0]);
            $infoRamais[$name] = array(
                'name' => $name,
                'extension' => $username,
                'online' => false,
                'status' =>  $data[$name]['status'],
                'agent' => $data[$name]['agent']
            );
            return $infoRamais;
        }
        

        return $infoRamais;
    }
}