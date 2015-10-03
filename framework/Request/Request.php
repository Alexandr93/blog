<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 21:15
 */

namespace Framework\Request;


class Request
{
   public function __construct(){

   }

    public function isPost()
    {
        return true;
    }
    public function get(){

    }
    public function post(){
        return true;
    }

}