<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "banner".
 *
 * @property int $id
 * @property string $name
 * @property int $banner_type
 * @property int $file_type
 * @property string $file_and
 * @property string $file_ios
 * @property int $status
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property int $sort_order
 * @property int $map_type
 * @property int $map_to
 */
class Banner extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'banner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name',  'file_and', 'status', 'created_by', 'updated_by'], 'required'],
            [['banner_type', 'file_type', 'status', 'created_by', 'updated_by', 'sort_order', 'map_type'], 'integer'],
            [['created_at', 'updated_at', 'promotion_id', 'promotion_from', 'promotion_to', 'sort_order', 'map_type', 'map_to', 'description_en', 'description_ar', 'save'], 'safe'],
            [['name', 'file_ios', 'file_and'], 'string', 'max' => 100],
            [['promotion_id'], 'exist', 'skipOnError' => true, 'targetClass' => PromotionalCampaign::className(), 'targetAttribute' => ['promotion_id' => 'id']],
            [['store'], 'exist', 'skipOnError' => true, 'targetClass' => Franchise::className(), 'targetAttribute' => ['store' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'banner_type' => 'Banner Type',
            'file_type' => 'File Type',
            'file_and' => 'File Android',
            'file_ios' => 'File Ios',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'sort_order' => 'Sort Order',
            'map_type' => 'Map Type',
            'map_to' => 'Map To',
            'promotion_id' => 'Promotion ID'
        ];
    }


}
