<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_notification_type".
 *
 * @property int $id
 * @property string $name
 * @property string $can_name
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property TblNotification[] $tblNotifications
 */
class NotificationType extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'notification_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'can_name', 'status', 'name_ar'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'updated_at', 'image'], 'safe'],
            [['name', 'can_name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'can_name' => 'Can Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'name_ar' => 'Name Arabic',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function uploadFile($file, $name, $folder) {

        $targetFolder = \yii::$app->basePath . '/../uploads/notification-type/';
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }
        if ($file->saveAs($targetFolder . $name . '.' . $file->extension)) {
            return true;
        } else {
            return false;
        }
    }

    public function getTblNotifications() {
        return $this->hasMany(TblNotification::className(), ['type_id' => 'id']);
    }

}
