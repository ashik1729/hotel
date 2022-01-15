<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "support_chat".
 *
 * @property string $id
 * @property int|null $sender
 * @property int|null $reciever
 * @property int $sender_type
 * @property int|null $reciever_type
 * @property string $message
 * @property string $file
 * @property string $created_at
 * @property int $created_by
 * @property int $created_by_type
 * @property int $status
 * @property int $read_status 1-read,0-Not read
 * @property int $updated_by
 * @property string $updated_at
 * @property int $updated_by_type
 * @property string $ticket_id
 *
 * @property SupportTickets $ticket
 */
class SupportChat extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'support_chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'sender_type', 'created_by', 'created_by_type', 'status', 'read_status', 'updated_by', 'updated_by_type', 'ticket_id'], 'required'],
            [['sender', 'reciever', 'sender_type', 'reciever_type', 'created_by', 'created_by_type', 'status', 'read_status', 'updated_by', 'updated_by_type'], 'integer'],
            [['message'], 'string'],
            [['created_at', 'updated_at', 'file', 'message'], 'safe'],
            [['id', 'ticket_id'], 'string', 'max' => 50],
            [['file'], 'string', 'max' => 150],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => SupportTickets::className(), 'targetAttribute' => ['ticket_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'sender' => 'Sender',
            'reciever' => 'Reciever',
            'sender_type' => 'Sender Type',
            'reciever_type' => 'Reciever Type',
            'message' => 'Message',
            'file' => 'File',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'created_by_type' => 'Created By Type',
            'status' => 'Status',
            'read_status' => 'Read Status',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'updated_by_type' => 'Updated By Type',
            'ticket_id' => 'Ticket ID',
        ];
    }

    /**
     * Gets query for [[Ticket]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTicket() {
        return $this->hasOne(SupportTickets::className(), ['id' => 'ticket_id']);
    }

    public function uploadFile($chat_id, $file, $name) {

        $targetFolder = \yii::$app->basePath . '/../uploads/support-chats/' . $chat_id . '/';
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }

        if ($file->saveAs($targetFolder . $name . '.' . $file->extension)) {
            return true;
        } else {
            return false;
        }
    }

}
