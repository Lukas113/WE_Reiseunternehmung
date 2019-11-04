<?php

namespace controllers;

use views\LayoutRendering;
use views\TemplateView;

/**
 * @author Andreas Martin
 */
class ErrorController
{
    public static function show404(){
        echo (new TemplateView("404.php"))->render();
    }
}