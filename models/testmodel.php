<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 03.05.2020
 * Time: 21:23
 */

namespace app\models;


use yii\base\Model;

class testmodel extends Model
{

    public $username;
    public $password;

    public function attributeLabels()
    {
        return [
            'login' => '',
            'password' => '',
        ];
    }
    public function rules(){
        return [
            [['login','password'], 'required' ],
            ['login', 'email'],
            ['password', 'string','min'=>3],


        ];
    }

}