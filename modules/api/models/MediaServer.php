<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "media_server".
 *
 * @property int $id
 * @property string $title
 * @property string $address
 * @property int $port
 * @property string $point
 * @property string $password
 *
 * @property Devices[] $devices
 */
class MediaServer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'media_server';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'address', 'port', 'point', 'password'], 'required'],
            [['port'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 255],
            [['point'], 'string', 'max' => 12],
            [['password'], 'string', 'max' => 32],
            [['title', 'address'], 'unique', 'targetAttribute' => ['title', 'address']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'address' => 'Address',
            'port' => 'Port',
            'point' => 'Point',
            'password' => 'Password',
        ];
    }

    /**
     * Gets query for [[Devices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDevices()
    {
        return $this->hasMany(Devices::className(), ['mserver_id' => 'id']);
    }
}
