<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Archive extends \yii\db\ActiveRecord
{
    /**
     * @return array the validation rules.
     */
     public static function tableName()
     {
         return 'user_archives';
     }
    public function rules()
    {
        return [
            // validate
            [['month_count'], 'required'],
            [['month_count'],'number'],
            [['from_date'],'date'],
            [['to_date'],'date']
        ];
    }

       public function attributeLabels()
    {
        return [
            'month_count'=>'',
            'from_date'=>'',
            'to_date'=>''
        ];
    }

}
