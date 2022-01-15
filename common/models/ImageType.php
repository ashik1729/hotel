<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "image_type".
 *
 * @property int $id
 * @property string $title
 * @property string $section_key
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by_type
 * @property int $updated_by_type
 * @property float $version
 */
class ImageType extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'image_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['title', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'version'], 'required'],
            [['status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type'], 'integer'],
            [['created_at', 'updated_at', 'section_key'], 'safe'],
            [['version'], 'number'],
            [['title'], 'string', 'max' => 50],
            [['section_key'], 'string', 'max' => 100],
            [['section_key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'section_key' => 'Section Key',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'version' => 'Version',
        ];
    }

}
