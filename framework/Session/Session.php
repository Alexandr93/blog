<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 08.10.15
 * Time: 19:17
 */

namespace Framework\Session;


use Framework\DI\Service;

class Session
{
  public $returnUrl;

   function removeAction($id){
       $model=Service::get('model');
       $res=$model->delete($id);
       $_SESSION::setMessage();
   }
}