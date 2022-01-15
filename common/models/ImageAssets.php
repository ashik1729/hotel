<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "image_assets".
 *
 * @property int $id
 * @property string $title
 * @property int $type
 * @property int $status
 * @property float $version
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by_type
 * @property int $updated_by_type
 */
class ImageAssets extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'image_assets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['title', 'type', 'status', 'version', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'device_type', 'store_id'], 'required'],
            [['image'], 'required', 'on' => 'create'],
            [['type', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type'], 'integer'],
            [['version'], 'number'],
            [['created_at', 'updated_at', 'sort_order', 'description_en', 'description_ar'], 'safe'],
            [['title'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'type' => 'Type',
            'status' => 'Status',
            'version' => 'Version',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'image' => 'Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
        ];
    }

    public function getImageTypes() {
        return $this->hasOne(ImageType::className(), ['id' => 'type']);
    }

    public function getStore() {
        return $this->hasOne(Franchise::className(), ['id' => 'store_id']);
    }

}
