<?php

namespace Source\Controllers;
use Source\Libs\StateMonitor;
use Exception;
use Source\Models\ExtensionModel;

class MonitorController
{

    /**
     * Display listing all extensions
     *
     */
    public function index(){
        header("Content-type: application/json; charset=utf-8");

        $monitor = new StateMonitor();

        $extensions = $monitor->generate();

        if(empty($extensions)){
            throw new Exception("Cannot get data monitor");
        }

        foreach($extensions as $ext){
            $data = $ext;
            $data['online'] = $ext['online'] ? 1 : 0;
            ExtensionModel::updateOrCreate($data);
        }

        echo json_encode($extensions, JSON_FORCE_OBJECT);
    }

}
