<?php
namespace Framework;

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 17:29
 */
use Framework\DI\Service;
use Framework\Exception\DatabaseException;
use Framework\Exception\HttpNotFoundException;
use Framework\Exception\SecurityException;
use Framework\Renderer\Renderer;
use Framework\Request\Request;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;
use Framework\Response\ResponseInterface;
use Framework\Router\Router;
use Framework\Security\Csrf;
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
    protected $pdo = [];

    public function __construct($configFile)
    {
        $this->config = include($configFile);
        Service::set('request', new Request());
        Service::set('security', new Security());

        $req = Service::get('request');
        $this->request = $req->getUri();
        Service::set('route', new Router($this->request, $this->config['routes']));
        $this->pdo = $this->config['pdo'];
        try {
            Service::set('db', new \PDO($this->pdo['dns'], $this->pdo['user'], $this->pdo['password']));
        } catch (\PDOException $e) {
            echo 'Connection error' . $e->getMessage();
        }
        Service::set('app', $this);
        Service::set('session', new Session());
        Service::set('csrf', new Csrf());
    }


    /**
     * @throws SecurityException
     */
    public function run()
    {
        $route = Service::get('route');
        $routes = $route->testUri();

        if (!empty($routes['security'])) {//check authorization on security pages
            $session = Service::get('session');
            $user = $session->get('user');
            if (!empty($user)) {
                if ($user->role == 'ROLE_ADMIN') {
                } else {
                    throw new SecurityException('You are not allowed posts adding', Service::get('route')->buildRoute('home'));
                }
            } else {
                throw new SecurityException('Authorization Required', Service::get('route')->buildRoute($this->config['security']['login_route']));
            }

        }


        try {

            if (!empty($routes)) {
                if (class_exists($routes['controller'])) {
                    $controller = $routes['controller'];
                    $response = $this->generateResponseCtrl($controller, $routes['action'], $routes);
                    if ($response instanceof ResponseInterface) {
                        if ($response->type == 'html') {
                            $content['content'] = $response->getContent();
                            $content['flush'] = Service::get('session')->getFlushMessage();

                            $renderer = new Renderer();
                            $response = new Response($renderer->render($this->config['main_layout'], $content));
                        }


                    } else {
                        throw new HttpNotFoundException('Bad response', 404);
                    }
                } else {
                    throw new HttpNotFoundException('Controller not found', 404);
                }
            } else {
                throw new HttpNotFoundException('Route not found', 404);
            }

        } catch (HttpNotFoundException $e) {
            $renderer = new Renderer();
            $response = new Response($renderer->render($this->config['error_500'],
                array('message' => $e->getMessage() , 'code' => $e->getCode()
                ))
            );
            $response= new Response($renderer->render($this->config['main_layout'], array('content'=>$response->getContent(), 'flush' => Service::get('session')->getFlushMessage())));

        }
        catch(DatabaseException $e){
            $renderer = new Renderer();
            $response = new Response($renderer->render($this->config['error_500'],   array('message' => $e->getMessage(), 'code'=>$e->getCode())));
        }
        $response->send();
    }

    /**
     * generate new Response object according to controller, and action
     * @param $controller Response namespace
     * @param $action
     * @param $routes
     * @return Response
     */
    public function generateResponseCtrl($controller, $action, $routes)
    {

        $action = $action . 'Action';
        $ctrlReflection = new \ReflectionMethod($controller, $action);
        $response = $ctrlReflection->invokeArgs(new $controller, array((isset($routes['id'])) ? $routes['id'] : []));
        return $response;
    }
}
