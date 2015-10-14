<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 21:16
 */

namespace Framework\Response;



class Response extends AbstrResponse implements ResponseInterface
{



    public $type = 'html';
    public function __construct($content, $code=201, $msg='Ok'){
       parent::__construct();
        $this->code=$code;
        $this->msg=$msg;
        $this->setContent($content);
    }

    public function __toString() {
        $result = $this->getContent();
        return $result.'';
    }

}