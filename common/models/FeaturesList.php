<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "features_list".
 *
 * @property int $id
 * @property string $title
 * @property string $name_en
 * @property string $name_ar
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_by_type
 * @property int $updated_by_type
 * @property int $sort_order
 */
class FeaturesList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'features_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'name_en', 'name_ar', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'sort_order'], 'required'],
            [['status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'sort_order'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'name_en', 'name_ar'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'name_en' => 'Name En',
            'name_ar' => 'Name Ar',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'sort_order' => 'Sort Order',
        ];
    }
}
