<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_attributes".
 *
 * @property int $id
 * @property int $product_id
 * @property int $attributes_id
 * @property int $status 1-Price Applicable,0-Not Applicable
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_by_type
 * @property int $updated_by_type
 * @property int $price_status 1-Price Applicable,0-Not Applicable
 *
 * @property Attributes $attributes0
 * @property ProductsServices $product
 */
class ProductAttributes extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'product_attributes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['product_id', 'attributes_id', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'price_status'], 'required'],
            [['product_id', 'attributes_id', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'price_status'], 'integer'],
            [['created_at', 'updated_at', 'price_status'], 'safe'],
            [['attributes_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attributes::className(), 'targetAttribute' => ['attributes_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductsServices::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'attributes_id' => 'Attributes ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'price_status' => 'Price Status',
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
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct() {
        return $this->hasOne(ProductsServices::className(), ['id' => 'product_id']);
    }

}
