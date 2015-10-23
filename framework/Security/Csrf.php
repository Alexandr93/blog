<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.10.15
 * Time: 10:01
 */

namespace Framework\Security;

use Framework\DI\Service;
use Framework\Exception\SecurityException;
class Csrf
{
    public function generateToken(){

        $session=Service::get('session');
        $session->delete('token');
        $token=md5(time());
        $session->set('token', $token);
        return $token;
    }
    public function checkToken(){
        $session=Service::get('session');
        $token=null;
        if(isset($_REQUEST['token'])){
            $token=$_REQUEST['token'];

        }
        if($token!=null){
            $sesTokenValue=$session->get('token');
           // $session->delete('token');
            return $sesTokenValue==$token ? true : false;
        }else{
            return true;
        }
    }
    public function checkTokenValid(){

        if($this->checkToken()==false)
            throw new SecurityException('Invalid token, resend form ');
    }
}