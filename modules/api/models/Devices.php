<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "devices".
 *
 * @property int $id
 * @property string $guid
 * @property string $code
 * @property int $mserver_id
 * @property int|null $user_id
 * @property string|null $description
 *
 * @property Cameras[] $cameras
 * @property MediaServer $mserver
 * @property User $user
 */
class Devices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    const SCENARIO_CREATE = 'create';

    public static function tableName()
    {
        return 'devices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid', 'code', 'mserver_id'], 'required'],
            [['mserver_id', 'user_id'], 'integer'],
            [['guid', 'code'], 'string', 'max' => 34],
            [['description'], 'string', 'max' => 30],
            [['guid'], 'unique'],
            [['code'], 'unique'],
            ['code', 'match', 'pattern' => '/^(\d{4})[-](\d{4})[-](\d{4})[-](\d{4})[-](\d{4})[-](\d{4})[-](\d{4})/', 'message' => 'ID, должен быть в формате ХXXX-ХХXX-XXXX-XXXX-XXXX-XXXX-XXXX'],
            [['mserver_id'], 'exist', 'skipOnError' => true, 'targetClass' => MediaServer::class, 'targetAttribute' => ['mserver_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['guid', 'code', 'mserver_id','jwt']; //Scenario Values Only Accepted
        return $scenarios;
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'guid' => 'Guid',
            'code' => 'Code',
            'mserver_id' => 'Mserver ID',
            'user_id' => 'User ID',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Cameras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCameras()
    {
        return $this->hasMany(Cameras::className(), ['device_id' => 'id']);
    }

    /**
     * Gets query for [[Mserver]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMserver()
    {
        return $this->hasOne(MediaServer::className(), ['id' => 'mserver_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
