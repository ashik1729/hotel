<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "events".
 *
 * @property int $id
 * @property string $title_en
 * @property string $title_ar
 * @property string|null $description_en
 * @property string|null $description_ar
 * @property string $date_time
 * @property string|null $file
 * @property string|null $gallery
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $created_at
 * @property string $updated_at
 * @property int $sort_order
 */
class Events extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['title_en', 'title_ar', 'date_time', 'status', 'store_id'], 'required', 'on' => 'create'],
            [['description_en', 'description_ar', 'gallery'], 'string'],
            [['date_time', 'created_at', 'updated_at', 'store_id', 'title_en', 'title_ar', 'date_time', 'status', 'store_id', 'country', 'city', 'place', 'place_ar'], 'safe'],
            [['status', 'created_by', 'updated_by', 'sort_order'], 'integer'],
            [['title_en', 'title_ar', 'file'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title_en' => 'Title En',
            'title_ar' => 'Title Ar',
            'description_en' => 'Description En',
            'description_ar' => 'Description Ar',
            'date_time' => 'Date Time',
            'file' => 'File',
            'gallery' => 'Gallery',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'sort_order' => 'Sort Order',
        ];
    }

    public function getCountry0() {
        return $this->hasOne(Country::className(), ['id' => 'country']);
    }

    public function getCity0() {
        return $this->hasOne(City::className(), ['id' => 'city']);
    }

}
