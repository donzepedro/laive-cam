<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 03.05.2020
 * Time: 13:51
 */


namespace app\models;
use yii\base\Model;
use yii;

class RegForm extends yii\db\ActiveRecord
{
//    public $email;
//    public $password;
//    public $name;
//    public $phone;
    public $newsagree;
    public $useragreement;
    public $sendername;
    public $subject;
    public $body;

    public function rules()
    {
            return [
                [['email'], 'required'],
                ['email', 'email'],
                [['name'], 'required'],
                [['phone'], 'required'],
                [['phone'], 'string'],
                // ['phone', 'match', 'pattern' => '/^(\+7)[(](\d{3})[)](\d{3})[-](\d{2})[-](\d{2})/', 'message' => 'Телефона, должно быть в формате 8(XXX)XXX-XX-XX'],
                [['password'], 'required'],
                [['password'],  'string', 'min' => 3],
                [['useragreement'],'compare', 'compareValue' => true,'message' => Yii::t('commonEn','Чтобы продолжить, установите этот флажок')],
                ['newsagree','boolean'],
            ];
    }

    public function attributeLabels()
    {
            return [

                'email' => '',
                'password' => '',
                'name' => '',
                'phone' => '',
                'type' => '',
                'newsagree' => '',
                'useragreement'=> ''
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
