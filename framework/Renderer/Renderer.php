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


    public function render($path_to_layout, $content)
    {

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


            $csrf = Service::get('csrf');
            $token = $csrf->generateToken();
            echo '<input type="hidden" name="token" value="' . $token . '" />';

        };

        ob_start();
        if (is_array($content)) {
            extract($content);
        }


        include($path_to_layout);

        //костыль на отобжажение ссылки на редактирование поста, чтобы не менять стандартную вьюху show.html
        if (!empty($content['post']->id) && Service::get('security')->isAuthenticated() && empty($content['show'])) {
            echo '<br/><a href="/posts/' . $content['post']->id . '/edit"> Edit post</a>';

        }
        return ob_get_clean();
    }

}