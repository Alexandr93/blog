<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 21:10
 */

namespace Framework\DI;
/**
 * Class Service
 * @package Framework\DI
 */

class Service
{
    protected static $services=array();
    protected static $instance;

    public function __construct(){

    }
    private function __clone(){}

    public static function getInstance(){

        if(null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function set($service_name, $object){

        self::$services[$service_name]=$object;

    }

    public static function get($service_name){
        return empty(self::$services[$service_name]? null : self::$services[$service_name]);
    }
}