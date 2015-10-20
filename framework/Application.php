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
use Framework\Response\ResponseRedirect;
use Framework\Response\ResponseInterface;
use Framework\Router\Router;
use Framework\Security\Security;
use Framework\Session\Session;

/**
 * Class Application
 * главный клас приложения
 */
class Application
{

    protected $config;
    protected $request;
    protected $pdo=[];
    /**
     * главный метод класа
     * @param $config для вытаскивания конфигов
     */
    public function __construct($configFile)
    {
        //
        $this->config = include($configFile);



        //
        Service::set('request', new Request());
        Service::set('security', new Security());

        $req=Service::get('request');
        $this->request=$req->getUri();
        Service::set('route', new Router($this->request, $this->config['routes']));
        $this->pdo=$this->config['pdo'];
        try {
            Service::set('db', new \PDO($this->pdo['dns'], $this->pdo['user'], $this->pdo['password']));
        }catch (\PDOException $e){
            echo 'Connection error'.$e->getMessage();
        }
        Service::set('app', $this);
        Service::set('session', new Session());
        Service::set('security', new Security());
       // Service::set('')
    }


    public function run(){
        //принимает карту маршрутов
        //print_r($this->pdo);
        //Service::set('app', new Application($this->config));
       $security=Service::get('security');
       // $security->setUser();
        //создать юзера не залогиненного при входе на сайт
        $route=Service::get('route');
        $routes=$route->testUri();


        if(!empty($routes['security'])){
            $user=Service::get('session')->get('user');


            }
        else{

        }

        Service::get('session')->returnUrl=Service::get('request')->getReferrer();
        if(!empty($routes)) {
            if(class_exists($routes['controller'])) {
                $controller = $routes['controller'];
               // $action = $routes['action'] . 'Action';
                $response=$this->generateResponseCtrl($controller, $routes['action'], $routes);




                if($response instanceof ResponseInterface){
                    if($response->type =='html') {
                        $content['content']= $response->getContent();
                        $content['flush']= Service::get('session')->getFlushMessage();
                        $renderer=new Renderer();
                        $response=new Response($renderer->render($this->config['main_layout'], $content));//content flush



                     }


                }
                //else{ throw BadResponse();}
                $response->send();
            }
        }

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

    public function generateResponseCtrl($controller, $action, $routes){

        $action = $action . 'Action';
        $ctrlReflection=new \ReflectionMethod($controller, $action);
        $response=$ctrlReflection->invokeArgs(new $controller, array((isset($routes['id'])) ? $routes['id']:[]));
       // $response->send();
        return $response;
    }
}