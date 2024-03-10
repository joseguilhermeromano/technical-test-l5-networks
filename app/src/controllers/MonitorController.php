<?php

namespace Source\Controllers;
use Source\Libs\StateMonitor;
use Exception;
use Source\Libs\ExtensionsGenerator;
use Source\Libs\QueuesGenerator;
use Source\Models\ExtensionModel;

class MonitorController
{

    /**
     * Display listing all extensions
     *
     */
    public function index(){
        header("Content-type: application/json; charset=utf-8");

        if(!isset($_SESSION['start'])){
            $_SESSION['start'] = true;
            $monitor = new StateMonitor();
        }else{
            $queues = new QueuesGenerator(__dir__.'/../libs/filas');
            $fileQueues = $queues->generate();
            $extensions = new ExtensionsGenerator(__dir__.'/../libs/ramais');
            $fileExtensions = $extensions->generate();
            $monitor = new StateMonitor($fileQueues, $fileExtensions);
        }

        $extensions = $monitor->getState();

        if(empty($extensions)){
            throw new Exception("Cannot get data monitor");
        }

        foreach($extensions as $ext){
            $data = $ext;
            $data['online'] = $ext['online'] ? 1 : 0;
            ExtensionModel::updateOrCreate(['name', 'extension'], $data);
        }

        echo json_encode($extensions, JSON_FORCE_OBJECT);
    }

}
