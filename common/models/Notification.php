<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_notification".
 *
 * @property int $id
 * @property int $type_id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property int $receiver_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $reciever_type 1-User, 2 - Agent, 3-Service
 *
 * @property TblNotificationType $type
 * @property TblUserGroup $receiver
 */
class Notification extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['type_id', 'title', 'title_ar', 'receiver_id', 'status', 'reciever_type'], 'required'],
            [['type_id', 'receiver_id', 'status', 'reciever_type'], 'integer'],
            [['created_at', 'updated_at', 'image', 'description_ar', 'description', 'redirection', 'redirection_id'], 'safe'],
            [['title', 'description', 'content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'receiver_id' => 'Receiver ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'reciever_type' => 'Reciever Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType() {
        return $this->hasOne(NotificationType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver() {
        return $this->hasOne(Users::className(), ['id' => 'receiver_id']);
    }

}
