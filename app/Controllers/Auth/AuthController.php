<?php


namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{
    public  function getSignOut($request, $response){

        $this->auth->logout();
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public  function getSignIn($request, $response){
        return $this->view->render($response, 'auth/signin.twig');
    }

    public  function postSignIn($request, $response){
        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if(!$auth){

            //set flash error message
            $this->flash->addMessage('error', 'Could not sign you in with those details.');

            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }
        return $response->withRedirect($this->router->pathFor('home'));
    }


    public  function getSignUp($request, $response){
        return $this->view->render($response, 'auth/signup.twig');
    }

    public  function postSignUp($request, $response){
        //var_dump($request->getParams());
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'name' => v::noWhitespace()->notEmpty()->alpha(),
            'password' => v::noWhitespace()->notEmpty(),
        ]);

        if($validation->failed()){
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        $user =  User::create([
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
            'password' => password_hash($request->getParam('password'),PASSWORD_DEFAULT),
        ]);

        //flash message
        $this->flash->addMessage('info', 'You have been signed up.');

        //automatically sign the user in after registering
        $this->auth->attempt($request->getParam('email'), $request->getParam('password'));

        return $response->withRedirect($this->router->pathFor('home'));
    }
}