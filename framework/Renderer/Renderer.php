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

    protected $layout;
    protected $data=array();
    
    public function __construct($view, $data){
        //Set inner vars
    }
    public function render(){
        ob_start();
        extract($this->data);
        include($this->layout);
        return ob_get_clean();
    }

}