<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 07.05.2020
 * Time: 22:24
 */

namespace app\models;
use yii\base\Model;
use yii;


class Notifications extends Model
{
    public $subscribe;
    public $ysubscribe;


    public function rules(){
        return [
            ['subscribe','boolean'],
            ['ysubscribe','boolean'],

        ];
    }
}