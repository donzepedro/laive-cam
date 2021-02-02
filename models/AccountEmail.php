<?php

namespace app\models;

use Yii;
use yii\base\Model;

class AccountEmail extends Model
{
    public $current_email;
    public $new_email;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // first_name, phone are required
            [['new_email'], 'required'],
            // validate
            ['new_email', 'email'],
            ['new_email', 'unique']
        ];
    }

       public function attributeLabels()
    {
        return [
            'current_email'=>'',
            'new_email'=>''
        ];
    }

}
