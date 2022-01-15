<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "attributes_value".
 *
 * @property int $id
 * @property int $attributes_id
 * @property string $value
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $created_by_type
 * @property int $updated_by
 * @property int $updated_by_type
 * @property int $sort_order
 *
 * @property Attributes $attributes0
 * @property ProductAttributesValue[] $productAttributesValues
 */
class AttributesValue extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'attributes_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['attributes_id', 'value', 'status', 'created_by', 'created_by_type', 'updated_by', 'updated_by_type'], 'required'],
            [['attributes_id', 'status', 'created_by', 'created_by_type', 'updated_by', 'updated_by_type', 'sort_order'], 'integer'],
            [['created_at', 'updated_at', 'created_at', 'updated_at'], 'safe'],
            [['value'], 'string', 'max' => 255],
            [['attributes_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attributes::className(), 'targetAttribute' => ['attributes_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'attributes_id' => 'Attributes ID',
            'value' => 'Value',
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
     * Gets query for [[Attributes0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttributes0() {
        return $this->hasOne(Attributes::className(), ['id' => 'attributes_id']);
    }

    /**
     * Gets query for [[ProductAttributesValues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributesValues() {
        return $this->hasMany(ProductAttributesValue::className(), ['attributes_value_id' => 'id']);
    }

}
