<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08-Jul-16
 * Time: 19:13
 */

namespace App\Controllers;


class Controller
{
    protected $container;
    public  function __construct($container){
        $this->container = $container;
    }

    public  function __get($property){
        if($this->container->{$property}){
            return $this->container->{$property};
        }
    }
}