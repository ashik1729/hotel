<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "marketing_notification".
 *
 * @property int $id
 * @property string $title_ar
 * @property string $title_en
 * @property string $description_en
 * @property string $description_ar
 * @property string $file
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $user_group
 * @property string $user
 * @property string $link
 * @property int $notification_type
 */
class MarketingNotification extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'marketing_notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['title_ar', 'title_en', 'description_en', 'description_ar', 'file', 'status', 'user_group', 'user', 'link', 'notification_type'], 'required'],
            [['description_en', 'description_ar', 'user', 'link'], 'string'],
            [['status', 'notification_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title_ar', 'title_en'], 'string', 'max' => 200],
            [['file'], 'string', 'max' => 100],
            [['user_group'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title_ar' => 'Title Ar',
            'title_en' => 'Title En',
            'description_en' => 'Description En',
            'description_ar' => 'Description Ar',
            'file' => 'File',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_group' => 'User Group',
            'user' => 'User',
            'link' => 'Link',
            'notification_type' => 'Notification Type',
        ];
    }

    public function upload($file, $image_name, $name = "") {

        $targetFolder = \yii::$app->basePath . '/../uploads/marketing-notification/';
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }
        if ($file->saveAs($targetFolder . $image_name . '.' . $file->extension)) {
            return true;
        } else {
            return false;
        }
    }

}
