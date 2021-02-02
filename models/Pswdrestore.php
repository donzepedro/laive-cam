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

class pswdrestore extends yii\db\ActiveRecord
{

//    public $newpassword;
//    public $confirmpassword;
//    public $email;
    public $sendername;
    public $subject;
    public $body;

    public function rules(){
        return [
            [['email'], 'required'],
            ['email', 'email'],
//            [['newpassword'], 'required'],
//            [['newpassword'],  'string', 'min' => 3],
//            ['confirmpassword', 'required'],
//            [['confirmpassword'],  'string', 'min' => 3],

        ];
    }
    public function attributeLabels()
    {
        return [
            'email'=>Yii::t('commonEn','Эл. почта'),
            'newpassword'=>'',
            'confirmpassword'=>''

        ];
    }

    public static function tableName()
    {
        return 'user';
    }

    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom(['zepedro@yandex.ru'=>$this->sendername])
//                ->setReplyTo([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        }
        return false;
    }

}