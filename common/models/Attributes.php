<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "attributes".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $created_by_type
 * @property int $updated_by
 * @property int $updated_by_type
 * @property int $sort_order
 *
 * @property AttributesValue[] $attributesValues
 */
class Attributes extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public $value;

    public static function tableName() {
        return 'attributes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'status', 'created_by', 'created_by_type', 'updated_by', 'updated_by_type'], 'required'],
            [['status', 'created_by', 'created_by_type', 'updated_by', 'updated_by_type', 'sort_order'], 'integer'],
            [['created_at', 'updated_at', 'sort_order', 'name_ar'], 'safe'],
//            ['name', 'unique'],
//            ['name', 'validateCountry'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'name_ar' => 'Name Arabic',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'created_by_type' => 'Created By Type',
            'updated_by' => 'Updated By',
            'updated_by_type' => 'Updated By Type',
            'sort_order' => 'Sort Order',
        ];
    }

    /**
     * Gets query for [[AttributesValues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttributesValues() {
        return $this->hasMany(AttributesValue::className(), ['attributes_id' => 'id']);
    }

}
