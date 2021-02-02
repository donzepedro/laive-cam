<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "records".
 *
 * @property int $id
 * @property int $camera_id
 * @property string $title
 * @property string $file_name
 * @property int $size
 * @property int $is_uploaded
 * @property string $datetime
 * @property string|null $description
 *
 * @property Cameras $camera
 */
class Records extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'records';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['camera_id', 'title', 'file_name', 'size'], 'required'],
            [['camera_id', 'size', 'is_uploaded'], 'integer'],
            [['datetime'], 'safe'],
            [['title', 'file_name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 21],
            [['file_name'], 'unique'],
            [['camera_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cameras::className(), 'targetAttribute' => ['camera_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'camera_id' => 'Camera ID',
            'title' => 'Title',
            'file_name' => 'File Name',
            'size' => 'Size',
            'is_uploaded' => 'Is Uploaded',
            'datetime' => 'Datetime',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Camera]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCamera()
    {
        return $this->hasOne(Cameras::className(), ['id' => 'camera_id']);
    }
}
