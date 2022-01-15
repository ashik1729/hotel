<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "merchant_category".
 *
 * @property int $id
 * @property string $name
 * @property string $name_ar
 * @property string|null $description
 * @property string|null $description_ar
 * @property string $image
 * @property int $status
 * @property string $created_at
 * @property string $updatet_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_by_type
 * @property int $updated_by_type
 * @property int|null $sort_order
 */
class MerchantCategory extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'merchant_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'name_ar', 'image', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type'], 'required'],
            [['description', 'description_ar'], 'string'],
            [['status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'sort_order'], 'integer'],
            [['created_at', 'updatet_at'], 'safe'],
            [['name', 'name_ar'], 'string', 'max' => 200],
            [['image'], 'string', 'max' => 100],
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
            'description' => 'Description',
            'description_ar' => 'Description Ar',
            'image' => 'Image',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updatet_at' => 'Updatet At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'sort_order' => 'Sort Order',
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
