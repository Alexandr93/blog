<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.09.15
 * Time: 15:46
 */

namespace Framework\Response;


class JsonResponse extends AbstrResponse
{

    public $type='json';
    protected $content;

    public function __construct($content){
        $this->setHeader('Content-Type: application/json');
        $this->setContent(json_encode($content));
    }

}