<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 08-Jul-16
 * Time: 18:57
 */

namespace App\Controllers;

use App\Models\User;

class HomeController extends Controller
{
    public  function index($request, $response){
        //http://localhost/slim3-application/public/?name=kanja
        //var_dump($request->getParam("name"));

        //check laravel documentation
//        $user1 = $this->db->table('users')->find(1);
//        $user = $this->db->table('users')->where('id', 1);
//        var_dump($user1);
//        var_dump($user);
//        var_dump($user->email)
//        die();
//        $user = User::find(1);
//        $user = User::where('email', 'kanja@gmail.com');
//        $user = User::where('email', 'kanja@gmail.com')->first();
//        var_dump($user);
//        var_dump($user->email);
//        die()

        return $this->view->render($response, 'home.twig');
    }
}