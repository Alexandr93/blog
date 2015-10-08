<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.10.15
 * Time: 19:23
 */

namespace Framework\Renderer;


class Renderer
{

    

    public function render($layout, $data){
       $include=function(){};

        ob_start();
        extract($this->data);
        include($this->layout);
        return ob_get_clean();
    }
    public function assign(){

    }
}