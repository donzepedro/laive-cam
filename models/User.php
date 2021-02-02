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

class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public function getArchiveMonth()
    {
        $result = 6;
        if ($archive = Archive::findOne(['user_id'=>$this->id])) {
            $result = $archive->month;
        }
        return $result;
    }

    public function getArchiveFrom()
    {
        $result = "";
        if ($archive = Archive::findOne(['user_id'=>$this->id])) {
            $result = explode(" ",$archive->from_date)[0];
        }
        return $result;
    }

    public function getArchiveTo()
    {
        $result = "";
        if ($archive = Archive::findOne(['user_id'=>$this->id])) {
            $result = explode(" ",$archive->to_date)[0];
        }
        return $result;
    }

}
