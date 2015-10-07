<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.09.15
 * Time: 15:45
 */

namespace Framework\Response;


use Framework\DI\Service;

class ResponseRedirect implements ResponseInterface
{
    protected $code;
    protected $url;
    protected $replace;
    public function __construct($url, $replace=true, $code=302){
        $this->code=$code;
        $this->url=$url;
        $this->replace=$replace;
    }
    public function send(){
        $request=Service::get('request');
        header('Referer: '.$request->fullUri());
        header('Location: '.$this->url, $this->replace, $this->code);
        //exit();
        return $this->url;
    }
}