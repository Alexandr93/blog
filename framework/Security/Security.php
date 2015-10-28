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


    /**
     * set user after login
     *
     * @param $user
     */
    public function setUser($user){
        $session=Service::get('session');
        $session->set('isAuth', true);
        $session->set('user', $user);
        $session->set('role', $user->role);


    }

    /**
     * check authenticated
     *
     * @return bool
     */
    public function isAuthenticated(){
       return Service::get('session')->get('isAuth')?true : false;

    }

    /**
     *clear session, delete user
     *
     */
    public function clear(){
        Service::get('session')->destroy();
    }
}
//CSRF
//<iframe>
//mimtype
//htaccess remote handlers
