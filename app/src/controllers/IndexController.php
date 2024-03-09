<?php

namespace Source\Controllers;
use Source\Controllers\WebController;

class IndexController extends WebController
{

    /**
     * Display listing all extensions
     *
     */
    public function index(){
        $this->renderView('monitor');
    }

}
