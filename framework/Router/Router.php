<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.09.15
 * Time: 18:31
 */

namespace Framework\Router;


class Router
{

   protected $map=array();
    //protected $pattern;
    //protected $controller;
    //protected $action;
    protected $method;
    protected $uri;//в будущем будем получать с реквеста
    protected $security;
    function __construct($requestUri, $routing_map){
        $this->map=$routing_map;
        $this->uri=$requestUri;
        //$this->parseUri($this->map);

    }


    public function testUri(){
        return $this->parseUri($this->map);
    }



    public function parseUri($map_array){

        $testUri=$this->uri;

            rtrim($testUri, '/');



        foreach($map_array as $route=>$routeValue){

                $requirements =isset($routeValue['_requirements'])?$routeValue['_requirements']:NULL;
                $pattern=preg_replace('~\{\w+\}~', isset($requirements['id']) ?'('.$requirements['id'].')':'[\w\d]+',
                    $routeValue['pattern']);
                if (preg_match( '~^'.$pattern.'$~', $testUri, $match)) {
                    $this->method=isset($requirements['_method'])?$requirements['_method']:NULL;
                    $this->security = isset($routeValue['security']) ? $routeValue['security'] : NULL;

                    $mach_route = $map_array[$route];
                    $mach_route['_name']=$route;
                    if (!empty($match[1]))
                        $mach_route['id_value'] = $match[1];//вытаскиваем значение поля id

                    return $mach_route;
                }


            }

    }
    public function getMethod(){
        return $this->method;
    }
    public function getSecurity(){
        return $this->security;
    }

    public function buildRoute(){

    }
}
