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

    

    public function render($path_to_layout, $content){

         $include=function($controller, $action, $params=array()){

          $app= Service::get('app');
                $app->generateResponseCtrl($controller, $action, $params);

       };
        $user=Service::get('session')->get('user');//массив

       //$flush = array();
        $router=Service::get('route');
       $getRoute = function($rout) use (&$router) {

            return $router->buildRoute($rout);
        };
        $generateToken=function(){
            $session=Service::get('session');

            if(array_key_exists('token', $_SESSION)){
                echo '<input type="hidden" name="token" value="'.$session->get('token').'" />';
                return $session->get('token');
            }else{
                $session->set('token', time());//
                echo '<input type="hidden" name="token" value="'.$session->get('token').'" />';

                return $session->get('token');
            }



        };
        //print_r($content['flush']);
        //print_r($content);
        ob_start();
        if(is_array($content)){
            extract($content);
        }


        include($path_to_layout);
        return ob_get_clean();
    }

}