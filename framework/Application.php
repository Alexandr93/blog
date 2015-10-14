<?php
namespace Framework;
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 17:29
 */
use Framework\DI\Service;
use Framework\Renderer\Renderer;
use Framework\Request\Request;
use Framework\Response\Response;
use Framework\Response\ResponseInterface;
use Framework\Router\Router;
use Framework\Security\Security;

/**
 * Class Application
 * главный клас приложения
 */
class Application
{

    protected $config;
    protected $request;//'/test_redirect';//потом будем получать с реквеста
    protected $pdo=[];
    /**
     * главный метод класа
     * @param $config для вытаскивания конфигов
     */
    public function __construct($configFile)
    {
        $this->config = include($configFile);




        Service::set('request', new Request());
        Service::set('security', new Security());
        $req=Service::get('request');
        $this->request=$req->getUri();
        Service::set('route', new Router($this->request, $this->config['routes']));
        $this->pdo=$this->config['pdo'];
        Service::set('db', new \PDO($this->pdo['dns'], $this->pdo['user'], $this->pdo['password']));
    }


    public function run(){
        //принимает карту маршрутов
        //print_r($this->pdo);
       $route=Service::get('route');
        $routes=$route->testUri();
        if(!empty($routes)) {
            if(class_exists($routes['controller'])) {
                $controller = $routes['controller'];
                $action = $routes['action'] . 'Action';
                $ctrlReflection=new \ReflectionMethod($controller, $action);



                $response=$ctrlReflection->invokeArgs(new $controller, array((isset($routes['id_value'])) ? $routes['id_value']:[]));
                $renderer=new Renderer();
               // $response->setContent($renderer->render(, $response->getContent()));

                if($response instanceof ResponseInterface){
                    $resp=new Response($renderer->render($this->config['main_layout'], null));
                    $resp->send();


                    if($response->type =='html') {

                       //return 'fsfsdfs';
                   }


                     $response->send();

                }
                //else{ throw BadResponse();}



            }
        }



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