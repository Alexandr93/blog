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
  public $returnUrl;//присваиваем значение при редиректе, для отправки на предидущую страницу
public function __construct(){
    $this->start();
}
    public function start(){
        session_start();
    }
    public function destroy(){
        session_destroy();
    }

    public function set($name, $value){
        $_SESSION[$name]=$value;
    }
    public function get($name){
        return empty($_SESSION[$name])? NULL : $_SESSION[$name];
    }
   public function delete($name){
       unset($_SESSION[$name]);
   }

    public function addFlushMessage($type, $msg){
        if(isset($msg)){
            $sesMsg=$this->get('flush');
                $sesMsg[$type][]=$msg;
        $this->set('flush', $sesMsg);
        }
    }
    public function getFlushMessage(){
        $flushMsg = $this->get('flush');
        if($flushMsg === NULL)
            $flushMsg = array();
        else{
            $this->delete('flush');
        }
        return $flushMsg;
    }
}