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
use Framework\Router\Router;

/**
 * Class Controller
 * @package Framework\Controller
 */

abstract class Controller
{

    /**
     * remake of the name name to full path for this views
     * @param $layout
     * @param array $data
     * @return Response
     */
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

    /**
     * get Request object
     * @return Request
     */
    public function getRequest(){
        return Service::get('request');

    }

    /**
     * redirect with url, and set Flush message to the session
     * @param $url
     * @param null $msg
     * @param string $type
     * @return ResponseRedirect
     */
    public function redirect($url, $msg=null, $type='success'){
        $ses=Service::get('session');
        $ses->addFlushMessage($type, $msg);
        return new ResponseRedirect($url);
    }

    /**
     * take url with $name in the Router
     * @param $name
     * @return Router
     */
    public function generateRoute($name){
        $router=Service::get('route');

        return $router->buildRoute($name);
    }
}