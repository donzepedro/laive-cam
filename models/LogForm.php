<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 23.04.2020
 * Time: 13:16
 */

namespace app\models;
use yii\base\Model;
use yii;

class LogForm extends Model
{
    public $id;
    public $login;
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
          [['login','password'], 'required' ,'message'=>Yii::t('commonEn', 'Обязательно к заполнению')],
            ['login', 'email'],
            ['password', 'string','min'=>3],


        ];
    }
}