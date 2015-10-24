<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.10.15
 * Time: 11:47
 */

namespace Framework\Exception;


use Framework\DI\Service;
use Framework\Response\ResponseRedirect;

class SecurityException extends \Exception
{
        public function __construct($msg, $url, $code=301){
            $session=Service::get('session');
            $session->setReturnUrl(Service::get('request')->getUri());
            $session->addFlushMessage('info', $msg);
            $resp = new ResponseRedirect($url, $code);
            $resp->send();
            //return  $resp;

        }
}