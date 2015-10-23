<?php
namespace Framework;
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 17:29
 */
use Framework\DI\Service;
use Framework\Exception\BadResponseException;
use Framework\Exception\DatabaseException;
use Framework\Exception\HttpNotFoundException;
use Framework\Renderer\Renderer;
use Framework\Request\Request;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;
use Framework\Response\ResponseInterface;
use Framework\Router\Router;
use Framework\Security\Csrf;
use Framework\Security\Security;
use Framework\Session\Session;
use Frameworkramework\Exception\ControllerNotFoundException;


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

        Service::set('csrf', new Csrf());
    }



    public function run(){




        $route=Service::get('route');
        $routes=$route->testUri();
        if(!empty($routes['security'])) {//check authorization on security pages
            $session = Service::get('session');
            $user = $session->get('user');
            $session->setReturnUrl(Service::get('request')->getUri());
            if(is_object($user)){
                if ($user->role == $routes['security'][0]) {}
                else{$session->addFlushMessage('info', 'You are not allowed');
                    $resp = new ResponseRedirect(Service::get('route')->buildRoute('home'));
                    $resp->send();
                    die;
                }
            }else{
                $session->addFlushMessage('info', 'Authorization Required');
                $resp = new ResponseRedirect(Service::get('route')->buildRoute($this->config['security']['login_route']));
                $resp->send();
                die;
            }

        }


    try{

    if(!empty($routes)) {
        if(class_exists($routes['controller'])) {
            $controller = $routes['controller'];
            // $action = $routes['action'] . 'Action';
            $response=$this->generateResponseCtrl($controller, $routes['action'], $routes);



            // print_r($response);
            if($response instanceof ResponseInterface){
                if($response->type =='html') {
                    $content['content']= $response->getContent();
                    $content['flush']= Service::get('session')->getFlushMessage();//нужно очищать

                    $renderer=new Renderer();
                    $response=new Response($renderer->render($this->config['main_layout'], $content));
                }


            } else{

                echo 'So bad';
                 throw new BadResponseException();

            }


        }else{
            //Exception controller not found
            echo 'Вы все говно';
            throw new ControllerNotFoundException();

        }
    }else{
        ///Exception route not found
        throw new HttpNotFoundException;

    }
    $response->send();
    }
    catch (HttpNotFoundException $e){}
    catch(DatabaseException $e){}
    catch(BadResponseException $e){}


    }

    /**
     * generate new Response object according to controller, and action
     * @param $controller Response namespace
     * @param $action
     * @param $routes
     * @return Response
     */
    public function generateResponseCtrl($controller, $action, $routes){

        $action = $action . 'Action';
        $ctrlReflection=new \ReflectionMethod($controller, $action);
        $response=$ctrlReflection->invokeArgs(new $controller, array((isset($routes['id'])) ? $routes['id']:[]));
        return $response;
    }
}
