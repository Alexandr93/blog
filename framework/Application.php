<?php
namespace Framework;
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 17:29
 */
use Framework\DI\Service;
use Framework\Request\Request;
use Framework\Response\Response;
use Framework\Router\Router;

/**
 * Class Application
 * главный клас приложения
 */
class Application
{

    protected $config;
    protected $request;//'/test_redirect';//потом будем получать с реквеста

    /**
     * главный метод класа
     * @param $config для вытаскивания конфигов
     */
    public function __construct($configFile)
    {
        $this->config = include($configFile);

        //Service::set('route', new Router('/test_redirect', $this->config['routes']));
        $this->request=$_SERVER['REQUEST_URI'];
        Service::set('route', new Router($this->request, $this->config['routes']));
        Service::set('request', new Request());

    }


    public function run(){
        //принимает карту маршрутов

       $route=Service::get('route');
        $routes=$route->testUri();
        if(!empty($routes)) {
            if(class_exists($routes['controller'])) {
                //$StdClass = ;
                $controller = $routes['controller'];

                $action = $routes['action'] . 'Action';
                $ctrlReflection=new \ReflectionMethod($controller, $action);

                $response=$ctrlReflection->invokeArgs(new $controller, (isset($routes['id_value'])) ? $routes['id_value']:[]);
                $s=$response->send();



            }
        }
        return $response;

     // return  get_class_methods($controller);
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