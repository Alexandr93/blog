<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14.10.15
 * Time: 14:44
 */

namespace Framework\Response;


abstract class AbstrResponse implements ResponseInterface
{
    protected $code;
    protected $headers=array();
    protected $content;
    protected $protocol;
    protected $responseStatus;
    protected $msg;
    public function __construct(){
        $this->protocol='HTTP/1.1';
    }
    public function setHeader($header){

        $this->headers[] = $header;
    }
    public function getHeaders(){
        foreach ($this->headers as $key =>$head) {
            header($head,true, $this->code);
        }
    }
    public function setContent($content) {
        $this->content = $content;
    }

    public function getContent() {
        return $this->content;
    }

    public function sendContent(){
        echo $this->content;
    }
    public function sendHttpHeader(){
        $header = "HTTP/".$this->protocol." ".$this->code." ".$this->responseStatus;
        header($header, true, $this->code);
    }
    public function send(){

        $this->sendHttpHeader();
        $this->getHeaders();
        $this->sendContent();

    }

}