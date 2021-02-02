<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 09.05.2020
 * Time: 19:12
 */

namespace app\models;
use yii\base\Model;
use yii;


class Changepswd extends yii\db\ActiveRecord
{
    public $oldpassword;
    public $newpassword;
    public $confirmpassword;


    public function rules(){
        return [
            [['oldpassword'], 'required'],
            [['oldpassword'],  'string', 'min' => 3],
            [['newpassword'], 'required'],
            [['newpassword'],  'string', 'min' => 3],
            ['confirmpassword', 'required'],
            [['confirmpassword'],  'string', 'min' => 3],

        ];
    }
    public function attributeLabels()
    {
        return [
            'oldpassword'=>'',
            'newpassword'=>'',
            'confirmpassword'=>''

        ];
    }
}