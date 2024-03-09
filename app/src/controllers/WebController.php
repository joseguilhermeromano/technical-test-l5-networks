<?php

namespace Source\Controllers;

class WebController
{

    protected function renderView($view, $variables = array())
    {
        $path = __dir__."/../views/$view.html";

        if (file_exists($path)) {

            extract($variables);

            include $path;
        } else {
            include __dir__."/../views/404.html";
        }
    }

}
