<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.10.15
 * Time: 19:23
 */

namespace Framework\Renderer;

use Framework\DI\Service;
class Renderer
{

    

    public function render($path_to_layout, $data){
       $include=function($controller, $action, $params=array()){
           $ctrlObj=new $controller;
           return call_user_func_array(array($ctrlObj,$action.'Action') , $params);
       };

        $getRoute = function($rout) {
            $router = Service::get('route');
            return $router->buildRoute($rout);
        };
        $generateToken=function(){};

        $data['include'] = $include;
        $data['getRoute'] = $getRoute;
        $data['generateToken'] = $generateToken;
        ob_start();
        extract($data);
        //print_r($path_to_layout);
        include($path_to_layout);
        return ob_get_clean();
    }

}