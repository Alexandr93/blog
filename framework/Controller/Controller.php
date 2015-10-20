<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 21:08
 */

namespace Framework\Controller;
use Framework\DI\Service;

use Framework\Response\Response;
use Framework\Response\ResponseRedirect;
use Framework\Renderer\Renderer;

/**
 * Class Controller
 * @package Framework\Controller
 */

abstract class Controller
{

    public function render($layout, $data=array()){

        $ctrl_class=get_class($this);
        $ctrl_namespace=explode('\\', $ctrl_class);
         $ctrl_name=str_replace('Controller', '', array_pop($ctrl_namespace));
        $path_to_layout=__DIR__.'/../../src/Blog/views/'.$ctrl_name.'/'.$layout.'.php';

        $renderer=new Renderer();
        $resp=new Response($renderer->render($path_to_layout, $data));
       // print_r($resp);

        return $resp;

    }
    public function getRequest(){
        return Service::get('request');

    }
    public function redirect($url, $msg=null){
        $ses=Service::get('session');
        $ses->addFlushMessage('successful', $msg);
        return new ResponseRedirect($url);
    }

    /**
     * @param $name
     * @return mixed получаем значение pattern с именем $name
     */
    public function generateRoute($name){
        $router=Service::get('route');

        return $router->buildRoute($name);
    }
}