<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.09.15
 * Time: 15:45
 */

namespace Framework\Response;


use Framework\DI\Service;

class ResponseRedirect extends AbstrResponse
{
    protected $code;
    protected $url;
    protected $replace;

    public function __construct($url, $code=301){
        parent::__construct();
        $this->code=$code;
        $this->url=$url;
        $request=Service::get('request');
        $this->setHeader('Referer: '.$request->fullUri());
        $this->setHeader('Location: '.$this->url, true, $this->code);

    }

    /**
     *
     */
    public function send(){
        $this->getHeaders();
    }
}