<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "search_history".
 *
 * @property string $id
 * @property int $user_id
 * @property string $search_key
 * @property int|null $type_of_search  (1- Location Based on Proximity, 2 Based on map, 3- Based on availability and rating of a merchant
 * @property string|null $latitude
 * @property string|null $longitude
 * @property float|null $radius
 * @property int|null $rating
 * @property int $availability
 * @property string $created_at
 * @property string $updared_at
 * @property int $status
 * @property int $store
 */
class SearchHistory extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'search_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'user_id', 'search_key', 'status', 'store'], 'required'],
            [['user_id', 'type_of_search', 'rating', 'availability', 'status', 'store', 'availability'], 'integer'],
            [['radius'], 'number'],
            [['created_at', 'updared_at'], 'safe'],
            [['id'], 'string', 'max' => 255],
            [['latitude', 'longitude'], 'string', 'max' => 20],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'search_key' => 'Search Key',
            'type_of_search' => 'Type Of Search',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'radius' => 'Radius',
            'rating' => 'Rating',
            'availability' => 'Availability',
            'created_at' => 'Created At',
            'updared_at' => 'Updared At',
            'status' => 'Status',
            'store' => 'Store',
        ];
    }

}
