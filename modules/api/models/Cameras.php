<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "cameras".
 *
 * @property int $id
 * @property int $id_camera
 * @property int $device_id
 * @property string|null $title
 * @property string|null $model
 * @property string|null $type
 * @property string|null $description
 *
 * @property Devices $device
 * @property Records[] $records
 */
class Cameras extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cameras';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_camera', 'device_id'], 'required'],
            [['id_camera', 'device_id'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['model', 'type', 'description'], 'string', 'max' => 30],
            [['id_camera'], 'unique'],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Devices::className(), 'targetAttribute' => ['device_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_camera' => 'Id Camera',
            'device_id' => 'Device ID',
            'title' => 'Title',
            'model' => 'Model',
            'type' => 'Type',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Device]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Devices::className(), ['id' => 'device_id']);
    }

    /**
     * Gets query for [[Records]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecords()
    {
        return $this->hasMany(Records::className(), ['camera_id' => 'id']);
    }
}
