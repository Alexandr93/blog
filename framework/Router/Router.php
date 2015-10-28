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
    protected $method;
    protected $uri;
    protected $security;
    public $result=array();
    function __construct($requestUri, $routing_map){
        $this->map=$routing_map;
        $this->uri=$requestUri;


    }


    public function testUri(){
        return $this->parseUri($this->map);
    }


    /**
     * @param $map_array
     * @return mixed
     */
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
                    if (!empty($match[1])) {
                        $mach_route['id'] = $match[1];//вытаскиваем значение поля id
                    }
                    $this->result=$mach_route;
                    return $mach_route;
                }


            }


    }

    /**
     * @return mixed
     */
    public function getMethod(){
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getSecurity(){
        return $this->security;
    }

    /**
     * @param $route
     * @return null
     */
    public function buildRoute($route){
       if(array_key_exists($route, $this->map)){
           $rout_params = $this->map[$route];
           return $rout_params['pattern'];
       }
        return null;
    }
}
