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

    /**
     * set new object
     * @param $service_name
     * @param $object
     */
    public static function set($service_name, $object){
    if(empty(self::$services[$service_name]))
        self::$services[$service_name]=$object;

    }

    /**
     * get object if exists
     * @param $service_name
     * @return mixed
     */
    public static function get($service_name){
         if(array_key_exists($service_name, self::$services)){
             return self::$services[$service_name];
         }
      /*   else {
             throw new ServiceNotFoundExeption();
         }*/
    }
}