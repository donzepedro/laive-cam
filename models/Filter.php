<?php

namespace app\models;

use Yii;
use yii\base\Model;


class Filter extends Model
{
    public $time_s;
    public $time_po;
    public $searchtitle;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // validate
            ['time_s', 'date'],

            ['time_po', 'date'],
            ['searchtitle', 'string']
        ];
    }

       public function attributeLabels()
    {
        return [
            'time_s'=>'',
            'time_po'=>'',
            'searchtitle'=>''
        ];
    }

}
