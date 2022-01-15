<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment_options".
 *
 * @property int $id
 * @property string $name
 * @property string $name_ar
 * @property string $image
 * @property int $status
 * @property int|null $sort_order
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_by_type
 * @property int $updated_by_type
 */
class PaymentOptions extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'payment_options';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'name_ar', 'image', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type'], 'required'],
            [['status', 'sort_order', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type'], 'integer'],
            [['name', 'name_ar'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'name_ar' => 'Name Ar',
            'image' => 'Image',
            'status' => 'Status',
            'sort_order' => 'Sort Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
        ];
    }

    public function uploadFile($file, $name, $folder) {

        $targetFolder = \yii::$app->basePath . '/../uploads/' . $folder . '/';
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
