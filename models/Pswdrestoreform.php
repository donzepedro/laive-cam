<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 10.05.2020
 * Time: 10:21
 */
namespace app\models;
use yii\base\Model;
use yii;

class pswdrestoreform extends yii\db\ActiveRecord
{

    public $newpassword;
    public $confirmpassword;

    public function rules(){
        return [

            [['newpassword'], 'required'],
            [['newpassword'],  'string', 'min' => 3],
            ['confirmpassword', 'required'],
            [['confirmpassword'],  'string', 'min' => 3],

        ];
    }
    public function attributeLabels()
    {
        return [
            'newpassword'=>Yii::t('commonEn','Новый пароль'),
            'confirmpassword'=>Yii::t('commonEn','Подвердите пароль')

        ];
    }

    public static function tableName()
    {
        return 'user';
    }

}