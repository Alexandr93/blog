<?php
namespace Framework;
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 17:29
 */
use Framework\Response\Response;
use Framework\Router\Router;

/**
 * Class Application
 * главный клас приложения
 */
class Application
{

    protected $config;
    protected $request='/test_redirect';//потом будем получать с реквеста
    /**
     * главный метод класа
     * @param $config для вытаскивания конфигов
     */
    public function __construct($configFile)
    {
        $this->config = include($configFile);

    }
    public function run(){
        //принимает карту маршрутов

       $route=new Router($this->request, $this->config['routes']);
        $routes=$route->testUri();
        $StdClass=$routes['controller'];
        $controller=new $StdClass();

      return  get_class_methods($controller);
        // $route->parseUri('/');//принимает текущий uri
        //define controller


        //$controller=new stdClass();
        //$response=$controller->action();
        //if($response instanceof ResponseInterface){
        //if($response->type=='html')
        //{
        //$renderer=new Renderer();
        //$wrapped->render($mail_layout, array('content'=>$response->getContent());
        //$response=new Response($wrapped);
        //}
        //}else{
        //throw BadResponse.....
        //}
        //$response->send();
    }
}