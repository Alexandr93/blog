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
    if(isset($_SESSION['returnUrl'])){
       // $this->setReturnUrl(isset($_SERVER['HTTP_REFERER']));
        $this->returnUrl = $_SESSION['returnUrl'];
    }


    }

    /**
     *
     */
    public function start(){
        session_start();
    }


    public function destroy(){
        session_destroy();
    }

    /**
     * @param $name
     * @param $value
     */
    public function set($name, $value){
        $_SESSION[$name]=$value;
    }

    /**
     * @param $name
     * @return null
     */
    public function get($name){
        return empty($_SESSION[$name])? NULL : $_SESSION[$name];
    }

    /**
     * @param $name
     */
    public function delete($name){
       if(!empty($_SESSION[$name])){
            unset($_SESSION[$name]);
        }
    }

    /**
     * set url for redirect after authentication
     * @param $url
     */
    public function setReturnUrl($url){
        $this->set('returnUrl',$url);
        $this->returnUrl=$_SESSION['returnUrl'];

    }

    /**
     *
     * @param $type
     * @param $msg
     */
    public function addFlushMessage($type, $msg){
        if(isset($msg)){
            $sesMsg=$this->get('flush');
                $sesMsg[$type][]=$msg;
        $this->set('flush', $sesMsg);
        }
    }

    /**
     * @return array|null
     */
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