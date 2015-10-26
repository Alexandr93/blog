<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.10.15
 * Time: 11:56
 */

namespace CMS\Controller;

use Blog\Model\User;
use Framework\Controller\Controller;
use Framework\DI\Service;
use Framework\Exception\SecurityException;
use Framework\Exception\DatabaseException;
use Framework\Renderer\Renderer;
use Framework\Response\Response;

class ProfileController extends Controller
{
    public function updateAction()
    {

        $route = Service::get('route');

        if (Service::get('security')->isAuthenticated()) {
            if ($this->getRequest()->isPost()) {

                $user = Service::get('session')->get('user');


                if ($user->password == $this->getRequest()->post('password')
                    && $this->getRequest()->post('newpassword1') == $this->getRequest()->post('newpassword2')
                ) {
                    try {
                        $us = new User();
                        $us->email = $user->email;
                        $us->password = md5($this->getRequest()->post('newpassword1'));
                        $us->role = $user->role;

                        $us->update('email', $user->email);
                        return $this->redirect($this->generateRoute('profile'), 'The password update successfully');
                    } catch (DatabaseException $e) {
                        $errors = array($e->getMessage());
                    }
                } else {
                    return $this->redirect($this->getRequest()->getUri(), 'Password mismatch', 'error');
                }


            } else {
               // return $this->render('updateprofile.html', array('errors' => $errors));
                return $this->getAction();
            }
        } else {
            throw new SecurityException('Please, login', $route->buildRoute('login'));
        }
       // $renderer = new Renderer();
       // return new Response($renderer->render(__DIR__ . '/../../Blog/views/Post/add.html.php', array('action' => $this->generateRoute('edit'), 'post' => isset($post)?$post:null, 'show'=>'check', 'errors' => isset($error)?$error:null)));
        return $this->render('updateprofile.html', array('errors' => $errors));
    }

    public function getAction()
    {


        $user=Service::get('session')->get('user')->email;

        return $this->render('updateprofile.html', array('content'=>'It\'s personal pages', 'username'=>$user));
    }
}