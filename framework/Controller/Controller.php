<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 21:08
 */

namespace Framework\Controller;
use Framework\DI\Service;
use Framework\Request\Request;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;
use Framework\Renderer\Renderer;

/**
 * Class Controller
 * @package Framework\Controller
 */

abstract class Controller
{

    public function render($layout, $data){

        $ctrl_class=get_class($this);
        $ctrl_namespace=explode('\\', $ctrl_class);
         $ctrl_name=str_replace('Controller', '', array_pop($ctrl_namespace));
        $path_to_layout=__DIR__.'/../../src/Blog/views'.$ctrl_name.'/'.$layout.'.php';

        $renderer=new Renderer();

        return new Response($renderer->render($path_to_layout, $data));

    }
    public function getRequest(){
        return new Request();

    }
    public function redirect($url, $msg='Hello, redirect'){
        return new ResponseRedirect($url);
    }
    public function generateRoute($name, $params=array()){
        $router=Service::get('route');
       return $router->buildRoute($name, $params);
    }
}