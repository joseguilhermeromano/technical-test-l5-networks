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

        $monitor = new StateMonitor();

        echo $monitor->generate();
    }

}
