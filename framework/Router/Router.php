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

    protected function patternToRegexp($reg, $pattern=array()){

        if(!empty($pattern)){
            foreach($pattern as $key => $subpattern){
                $pattern=str_replace('{'.$key.'}', $subpattern, $pattern);
            }
        }

        $pattern=preg_replace('~\{[\w\d_]Ui+\}~', '~[\d]+~', $pattern);

        $regexp='/^'.$pattern.'$~i';

        return $regexp;
    }
    public function testUri(){
        return $this->parseUri($this->map);
    }
    public function findRoute($uri){

        $match_route=null;

        if(!empty($this->map)){
            foreach($this->map as $route){
                $pattern=$this->patternToRegexp($this->map['pattern']);

                if(preg_match($pattern, $uri)){
                    $match_route=$route;
            }
            }
        }
        return $this->map['pattern'];
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
                    if (!empty($match[1]))
                        $mach_route['id_value'] = $match[1];//вытаскиваем значение поля id
                    $this->msg = $mach_route;
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
