<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tax_class".
 *
 * @property int $id
 * @property string $tax_name
 * @property int $type
 * @property float $tax_rate
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ProductsServices[] $productsServices
 */
class TaxClass extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tax_class';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['tax_name', 'type', 'tax_rate', 'status', 'created_by', 'updated_by'], 'required'],
            [['id', 'type', 'status', 'created_by', 'updated_by'], 'integer'],
            [['tax_rate'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['tax_name'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'tax_name' => 'Tax Name',
            'type' => 'Type',
            'tax_rate' => 'Tax Rate',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[ProductsServices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductsServices() {
        return $this->hasMany(ProductsServices::className(), ['tax_amount' => 'id']);
    }

}
