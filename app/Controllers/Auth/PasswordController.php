<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10-Jul-16
 * Time: 08:07
 */

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use Respect\Validation\Validator as v;

class PasswordController extends  Controller
{
    public function getChangePassword($request, $response){
        return $this->view->render($response, 'auth/password/change.twig');
    }
    public function postChangePassword($request, $response){
        $validation = $this->validator->validate($request, [
            'password_old' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
            'password' => v::noWhitespace()->notEmpty(),
        ]);
        if($validation->failed()){
            return $response->withRedirecte($this->router-pathFor('auth.password.change'));
        }

        //invoke the User's model setPassword method
        $this->auth->user()->setPassword($request->getParam('password'));

        //flash message
        $this->flash->addMessage('info', 'Your password was changed.');

        //redirect
        return $response->withRedirect($this->router->pathFor('home'));
    }
}