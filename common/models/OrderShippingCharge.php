<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_shipping_charge".
 *
 * @property int $id
 * @property int $order_id
 * @property int $merchant_id
 * @property float $shipping_charge
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_by_type
 * @property int $updated_by_type
 *
 * @property Orders $order
 * @property Merchant $merchant
 */
class OrderShippingCharge extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'order_shipping_charge';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['order_id', 'merchant_id', 'shipping_charge', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'shipping_method'], 'required'],
            [['order_id', 'merchant_id', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type'], 'integer'],
            [['shipping_charge'], 'number'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['merchant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Merchant::className(), 'targetAttribute' => ['merchant_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'merchant_id' => 'Merchant ID',
            'shipping_charge' => 'Shipping Charge',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder() {
        return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }

    /**
     * Gets query for [[Merchant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant() {
        return $this->hasOne(Merchant::className(), ['id' => 'merchant_id']);
    }

}
