<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.09.15
 * Time: 15:46
 */

namespace Framework\Response;


class JsonResponse implements ResponseInterface
{
       public function __construct(){

       }
        public $type='json';
        public function send(){

    }
}