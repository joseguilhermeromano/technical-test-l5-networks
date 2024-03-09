<?php

namespace Source\Controllers;
use Source\Libs\StateMonitor;

class MonitorController
{

    /**
     * Display listing all extensions
     *
     */
    public function index(){
        header("Content-type: application/json; charset=utf-8");

        $monitor = new StateMonitor();

        echo $monitor->generate();
    }

}
