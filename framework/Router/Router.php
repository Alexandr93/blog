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
    protected $pattern;
    protected $controller;
    protected $action;
    function __construct($routing_map=array()){
        $this->map=$routing_map;
    }

    protected function patternToRegexp($pattern){

        if(!empty($pattern)){
            foreach($pattern as $key => $subpattern){
                $pattern=str_replace('{'.$key.'}', $subpattern, $pattern);
            }
        }

        $pattern=preg_replace('~\{[\w\d_]Ui+\}', '[\w\d]+', $pattern);

        $regexp='/^'.$pattern.'$~i';
        
        return $pattern;
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

    }

    public function buildRoute(){}
}
