<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string|null $name_en
 * @property string|null $name_ar
 * @property int|null $state
 * @property int|null $country
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_by_type
 * @property int|null $updated_by_type
 * @property int $sort_order
 * @property int|null $status
 *
 * @property Area[] $areas
 * @property States $state0
 * @property Country $country0
 */
class City extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['state', 'country', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'sort_order', 'status'], 'required'],
            [['state', 'country', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'sort_order', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name_en', 'name_ar'], 'string', 'max' => 200],
            [['state'], 'exist', 'skipOnError' => true, 'targetClass' => States::className(), 'targetAttribute' => ['state' => 'id']],
            [['country'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name_en' => 'Name In English',
            'name_ar' => 'Name In Arbic',
            'state' => 'State',
            'country' => 'Country',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'sort_order' => 'Sort Order',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas() {
        return $this->hasMany(Area::className(), ['city' => 'id']);
    }

    /**
     * Gets query for [[State0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getState0() {
        return $this->hasOne(States::className(), ['id' => 'state']);
    }

    /**
     * Gets query for [[Country0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry0() {
        return $this->hasOne(Country::className(), ['id' => 'country']);
    }

}
