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

    public $type='json';
    protected $content;
       public function __construct($content){
            $this->content=$content;

       }

        public function send(){

        return $this->content;
    }
}