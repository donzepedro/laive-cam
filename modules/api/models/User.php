<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int $isAdmin
 * @property string|null $name
 * @property string $email
 * @property string|null $phone
 * @property string $password
 * @property string $ActivateStatus
 * @property int $subscribe
 * @property int $status
 * @property string|null $surname
 * @property string|null $username
 * @property string|null $birthday
 * @property string|null $country
 * @property string|null $city
 * @property string|null $gender
 * @property int $type
 * @property string|null $auth_key
 * @property string|null $password_reset_token
 * @property int $social
 * @property string|null $social_name
 * @property string|null $social_id
 * @property string|null $ip
 * @property int $ysubscribe
 * @property string|null $company
 * @property string|null $analitica
 * @property string|null $smsemail
 * @property string $created_at
 * @property int $actiontimestamp
 *
 * @property Devices[] $devices
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['isAdmin', 'subscribe', 'status', 'type', 'social', 'ysubscribe', 'actiontimestamp'], 'integer'],
            [['email', 'password'], 'required'],
            [['password'], 'string'],
            [['birthday', 'created_at'], 'safe'],
            [['name', 'email', 'phone', 'ActivateStatus', 'surname', 'username', 'country', 'city', 'gender', 'auth_key', 'password_reset_token', 'social_name', 'social_id', 'ip', 'company', 'analitica', 'smsemail'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'isAdmin' => 'Is Admin',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'password' => 'Password',
            'ActivateStatus' => 'Activate Status',
            'subscribe' => 'Subscribe',
            'status' => 'Status',
            'surname' => 'Surname',
            'username' => 'Username',
            'birthday' => 'Birthday',
            'country' => 'Country',
            'city' => 'City',
            'gender' => 'Gender',
            'type' => 'Type',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
            'social' => 'Social',
            'social_name' => 'Social Name',
            'social_id' => 'Social ID',
            'ip' => 'Ip',
            'ysubscribe' => 'Ysubscribe',
            'company' => 'Company',
            'analitica' => 'Analitica',
            'smsemail' => 'Smsemail',
            'created_at' => 'Created At',
            'actiontimestamp' => 'Actiontimestamp',
        ];
    }

    /**
     * Gets query for [[Devices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDevices()
    {
        return $this->hasMany(Devices::className(), ['user_id' => 'id']);
    }
}
