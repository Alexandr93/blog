<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 21:15
 */

namespace Framework\Request;


use Framework\Controller\Controller;
use Framework\DI\Service;
use Framework\Response\ResponseRedirect;

class Request
{

    public function getMethod(){
        return trim($_SERVER['REQUEST_METHOD']);
    }
    public function getUri(){
        return trim($_SERVER['REQUEST_URI']);
    }
    public function isGet(){
        return 'GET'===$this->getMethod();
    }
    public function isPost()
    {
        return 'POST'===$this->getMethod();
    }
    public function getHost(){
        $host = '';
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on'))
            $host .= 'https://';
        else
            $host .= 'http://';
        $host .= $_SERVER['SERVER_NAME'];
        return $host;
    }
    public function getPort(){
        $port = 80;
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on'))
            $port = 443;
        return $port;
    }
    public function fullUri(){
        return $this->getHost().$this->getUri();
    }

    public function getReferrer(){
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
    }
    public function get($param){

        if(array_key_exists($param, $_GET))
            return $this->filter($_GET[$param]);
        return null;
    }
    public function post($param){
        //print_r($_POST);
        if(array_key_exists($param, $_POST)) {
            if ($param == 'password') {
                $_POST[$param]=md5($_POST[$param]);//шифрация пароля
            }
            Service::get('csrf')->checkTokenValid();

            return $this->filter($_POST[$param]);

        }
        return NULL;
    }


    public function filter($method){


            $result = $method;


        return $result;
    }
}