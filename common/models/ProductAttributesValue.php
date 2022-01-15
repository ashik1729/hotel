<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_attributes_value".
 *
 * @property int $id
 * @property int $attributes_value_id
 * @property int $product_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $created_by_type
 * @property int $updated_by
 * @property int $updated_by_type
 * @property int $sort_order
 *
 * @property AttributesValue $attributesValue
 * @property ProductsServices $product
 */
class ProductAttributesValue extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public $attribute_id;
    public $attribute_value;
    public $error;

    public static function tableName() {
        return 'product_attributes_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
//            [['attributes_value_id', 'product_id', 'status', 'created_by', 'created_by_type', 'updated_by', 'updated_by_type', 'sort_order'], 'required'],
            [['attributes_value_id', 'product_id', 'status', 'created_by', 'created_by_type', 'updated_by', 'updated_by_type', 'sort_order'], 'integer'],
            [['created_at', 'updated_at', 'quantity', 'price', 'error', 'price_status'], 'safe'],
            [['attributes_value_id'], 'exist', 'skipOnError' => true, 'targetClass' => AttributesValue::className(), 'targetAttribute' => ['attributes_value_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductsServices::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'attributes_value_id' => 'Attributes Value ID',
            'product_id' => 'Product ID',
            'status' => 'Status',
            'price_status' => 'Price Applicable',
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
     * Gets query for [[AttributesValue]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttributesValue() {
        return $this->hasOne(AttributesValue::className(), ['id' => 'attributes_value_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct() {
        return $this->hasOne(ProductsServices::className(), ['id' => 'product_id']);
    }

}
