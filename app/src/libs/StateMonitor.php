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

    protected function getStatusRamais()
    {
        $status = [];

        foreach ($this->filas as $linhas) {

            if (strstr($linhas, 'SIP/')) {
                if (strstr($linhas, '(Ring)')) {
                    $linha = explode(' ', trim($linhas));
                    list(, $ramal) = explode('/', $linha[0]);
                    $status[$ramal] = ['status' => 'chamando'];
                } elseif (strstr($linhas, '(In use)')) {
                    $linha = explode(' ', trim($linhas));
                    list(, $ramal) = explode('/', $linha[0]);
                    $status[$ramal] = ['status' => 'ocupado'];
                } elseif (strstr($linhas, '(Not in use)')) {
                    $linha = explode(' ', trim($linhas));
                    list(, $ramal) = explode('/', $linha[0]);
                    $status[$ramal] = ['status' => 'disponivel'];
                }
            }
        }

        return $status;
    }

    public function generate()
    {
        header("Content-type: application/json; charset=utf-8");

        $response = [];

        foreach ($this->ramais as $linhas) {

            $linha = array_filter(explode(' ', $linhas), function ($value) {
                return $value !== '' && $value !== null;
            });

            $values = array_values($linha);

            $ramaisOff = $this->getInfoRamaisOff($values);
            $ramaisOn = $this->getInfoRamaisOn($response, $values);

            if(!empty($ramaisOff)) array_push($response, $ramaisOff);
            if(!empty($ramaisOn)) array_push($response, $ramaisOn);
        }

        return json_encode($response);
    }

    protected function getInfoRamaisOff($values)
    {
        $fieldStatus = trim($values[4]) == 'UNKNOWN';
        $fieldHost = trim($values[1]) == '(Unspecified)';
        $infoRamais = [];

        $statusRamais =  $this->getStatusRamais();

        if(empty($statusRamais)) {
            throw new Exception("Cannot get status of 'ramais'");
        }

        if($fieldHost && $fieldStatus){
            list($name, $username) = explode('/', $values[0]);
            $infoRamais[$name] = array(
                'nome' => $name,
                'ramal' => $username,
                'online' => false,
                'status' => $statusRamais[$name]['status']
            );
            return $infoRamais;
        }
        

        return $infoRamais;
    }

    protected function getInfoRamaisOn($infoRamais, $values)
    {
        $fieldStatusIsOk = trim($values[5]) == "OK"; 

        $statusRamais =  $this->getStatusRamais();

        if(empty($statusRamais)) {
            throw new Exception("Cannot get status of 'ramais'");
        }

        if($fieldStatusIsOk){
            list($name, $username) = explode('/', $values[0]);
            $infoRamais[$name] = array(
                'nome' => $name,
                'ramal' => $username,
                'online' => true,
                'status' => isset($statusRamais[$name]['status']) ? $statusRamais[$name]['status'] : ''
            );

            return $infoRamais;
        }

        return $infoRamais;
    }
}