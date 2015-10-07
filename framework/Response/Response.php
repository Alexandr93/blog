<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 21:16
 */

namespace Framework\Response;



class Response implements ResponseInterface
{

    protected $content;
    protected $code;
    protected $msg;
    protected $headers;
    protected $protocols;
    public function __construct($content=null, $code=200, $msg='Successful'){
        $this->content=$content;
        $this->code=$code;
        $this->msg=$msg;
    }
    public function getContent(){}
    public function send(){}
}