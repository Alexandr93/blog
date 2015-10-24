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

        $include = function ($controller, $action, $params = array()) {

            $app = Service::get('app');
            $app->generateResponseCtrl($controller, $action, $params);

        };
        $user = Service::get('session')->get('user');//массив

        $router = Service::get('route');
        $getRoute = function ($rout) use (&$router) {

            return $router->buildRoute($rout);
        };
        $generateToken = function () {

            $session = Service::get('session');
            $csrf=Service::get('csrf');
            $token=$csrf->generateToken();
            echo '<input type="hidden" name="token" value="' . $token . '" />';

        };

        ob_start();
        if (is_array($content)) {
            extract($content);
        }


        include($path_to_layout);
        return ob_get_clean();
    }

}