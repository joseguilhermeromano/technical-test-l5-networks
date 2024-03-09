<?php
/**
 * Você deverá transformar em uma classe
 */
header("Content-type: application/json; charset=utf-8");
$ramais = file(__dir__.'/ramais');
$filas = file(__dir__.'/filas');
$status_ramais = [];
foreach($filas as $linhas){
    if(strstr($linhas,'SIP/')){
        $linha = explode(' ', trim($linhas));
        list(,$ramal) = explode('/',$linha[0]);
        $status_ramais[$ramal] = ['agent' => end($linha)];
        if (strstr($linhas, '(Ring)')) {
            $status_ramais[$ramal]['status'] = 'chamando';
        } elseif (strstr($linhas, '(In use)')) {
            $status_ramais[$ramal]['status'] = 'ocupado';
        } elseif(strstr($linhas, '(paused)')){
            $status_ramais[$ramal]['status'] = 'pausado';
        }elseif (strstr($linhas, '(Not in use)')) {
            $status_ramais[$ramal]['status'] = 'disponivel';
        } elseif (strstr($linhas, '(Unavailable)')) {
            $status_ramais[$ramal]['status'] = 'indisponivel';
        }
    }
}
$info_ramais = array();
foreach($ramais as $linhas){
    $linha = array_filter(explode(' ', $linhas), function($value) {
        return $value !== '' && $value !== null;
    });
    $arr = array_values($linha);
    if(trim($arr[1]) == '(Unspecified)' && trim(end($arr)) == 'UNKNOWN'){        
        list($name,$username) = explode('/',$arr[0]);        
        $info_ramais[$name] = array(
            'nome' => $name,
            'ramal' => $username,
            'online' => false,
            'status' => isset($status_ramais[$name]['status']) ? $status_ramais[$name]['status'] : '',
            'agente' => $status_ramais[$name]['agent']
        );
    }
    
    if(trim($arr[5]) == "OK"){        
        list($name,$username) = explode('/',$arr[0]);
        $info_ramais[$name] = array(
            'nome' => $name,
            'ramal' => $username,
            'online' => true,
            'status' => isset($status_ramais[$name]['status']) ? $status_ramais[$name]['status'] : '',
            'agente' => $status_ramais[$name]['agent']
        );
    }
}
echo json_encode($info_ramais);