<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_user_notification".
 *
 * @property int $id
 * @property int $user_id
 * @property int $notification_type
 * @property int $status 1-Enable, 2-Disable
 * @property string $created_at
 * @property string $updated_at
 */
class UserNotification extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'user_notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'notification_type'], 'required'],
            [['user_id', 'notification_type', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'notification_type' => 'Notification Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getType() {
        return $this->hasOne(NotificationType::className(), ['id' => 'notification_type']);
    }

    public function getUser() {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

}
