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

/**
 * Class Controller
 * @package Framework\Controller
 */

abstract class Controller
{

    public function render($layout, $data){
        $ctrl_class=get_class($this);
       $path_to_layout='...';

        $renderer=new Renderer($layout, $data);
        $renderer->assign('data', $data);
        return new Response($renderer->render);

    }
    public function getRequest(){
        return new Request();

    }
    public function redirect($url, $msg=''){
        return new ResponseRedirect();
    }
    public function generateRoute($name, $params=array()){
        $router=Service::get('router');
       return $router->buildRoute($name, $params);
    }
}