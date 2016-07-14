<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 08-Jul-16
 * Time: 18:47
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //explicitly define the model table
    protected $table = 'users';

    //define columns that can be written to
    protected $fillable = [
        'email',
        'name',
        'password',
    ];

    public function setPassword($password){
        $this->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}