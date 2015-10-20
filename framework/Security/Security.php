<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 10.10.15
 * Time: 18:32
 */

namespace Framework\Security;

use Framework\DI\Service;

class Security
{


    public function setUser($user){
        $session=Service::get('session');
        $session->set('isAuth', true);
        $session->set('user', $user);
        $session->set('role', $user->role);


    }

    public function isAuthenticated(){
       return Service::get('session')->get('isAuth')?true : false;

    }
    public function clear(){
        Service::get('session')->destroy();
    }
}
//CSRF
//<iframe>
//mimtype
//htaccess remote handlers

///токен проверка при POST запросе