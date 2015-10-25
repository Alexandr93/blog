<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.10.15
 * Time: 11:57
 */

namespace CMS\Controller;


use Framework\Controller\Controller;
use Framework\DI\Service;
use Framework\Exception\DatabaseException;
use Framework\Exception\SecurityException;
use Framework\Renderer\Renderer;
use Framework\Response\Response;
use Blog\Model\Post;
use Framework\Validation\Validator;


class BlogController extends Controller
{
    public function editAction($id){
    $route=Service::get('route');
//print_r(__DIR__);
        $post = Post::find((int)$id);
        $session = Service::get('session');
        $user = $session->get('user');
        if (Service::get('security')->isAuthenticated()) {
            if ($user->role == 'ROLE_ADMIN') {
                if ($this->getRequest()->isPost()) {
                    try {
                        $post = new Post();
                        $date = new \DateTime();
                        $post->title = $this->getRequest()->post('title');
                        $post->content = trim($this->getRequest()->post('content'));
                        $post->date = $date->format('Y-m-d H:i:s');
                        $validator=new Validator($post);
                        if ($validator->isValid()) {
                            $post->update('id', $id);
                            return $this->redirect($this->generateRoute('home'), 'The data has been update successfully');
                        } else {
                            $error = $validator->getErrors();
                        }
                    } catch (DatabaseException $e) {
                        $error = $e->getMessage();
                    }

                }

            } else {
                throw new SecurityException('You are not allowed posts adding', Service::get('route')->buildRoute('home'));
            }
        }else{
            throw new SecurityException('Please, login', $route->buildRoute('login'));
        }
        $renderer = new Renderer();
        return new Response($renderer->render(__DIR__ . '/../../Blog/views/Post/add.html.php', array('action' => $this->generateRoute('edit'), 'post' => isset($post)?$post:null, 'show'=>'check', 'errors' => isset($error)?$error:null)));
    }
}