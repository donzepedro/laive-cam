<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Account extends Model
{
    public $first_name;
    public $phone;
    public $surname;
    public $country;
    public $city;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // first_name, phone are required
            [['first_name', 'phone'], 'required'],
            // validate
            ['phone', 'string'],

            ['first_name', 'string'],
            ['surname', 'string'],
            ['country', 'string'],
            ['city', 'string']
        ];
    }

       public function attributeLabels()
    {
        return [
            'first_name'=>'',
            'phone'=>'',
            'surname'=>'',
            'country'=>'',
            'city'=>''
        ];
    }

}
