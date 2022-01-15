<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "authentication".
 *
 * @property int $id
 * @property int $user_id
 * @property string $auth_key
 * @property int $status
 * @property string $refresh_token
 * @property string $created_at
 * @property string $updated_at
 * @property string $device_id
 * @property string $fb_token
 * @property int $account_type_id
 * @property int $device_type
 *
 * @property User $user
 */
class Authentication extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'authentication';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'auth_key', 'status', 'refresh_token', 'device_id', 'fb_token', 'device_type', 'expiry_time'], 'required'],
            [['user_id', 'status', 'account_type_id', 'device_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
//            ['device_id', 'checkdevice', 'on' => 'guest_login'],
            [['auth_key', 'fb_token'], 'string', 'max' => 255],
            [['refresh_token'], 'string', 'max' => 200],
            [['device_id'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function checkdevice($attribute, $params) {
        if ($this->user_type == 1 || $this->user_type == 2) {

            $check_exist = Authentication::findOne(['device_id' => $this->device_id]);
            if ($check_exist && $check_exist->user->user_type == 3) {
                $this->addError($attribute, 'Email Already Exist in our record.');
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'auth_key' => 'Auth Key',
            'status' => 'Status',
            'refresh_token' => 'Refresh Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'device_id' => 'Device ID',
            'fb_token' => 'Fb Token',
            'account_type_id' => 'Account Type ID',
            'device_type' => 'Device Type',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
