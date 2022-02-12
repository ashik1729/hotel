<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_products".
 *
 * @property int $id
 * @property int $order_id
 * @property int $user_id
 * @property int $product_id
 * @property int $merchant_id
 * @property int $quantity
 * @property float $amount
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property Orders $order
 * @property User $user
 * @property ProductsServices $product
 * @property Merchant $merchant
 */
class OrderProducts extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'order_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['order_id', 'user_id', 'product_id', 'quantity', 'amount', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type'], 'required'],
            [['order_id', 'user_id', 'product_id', 'merchant_id', 'quantity', 'status', 'created_by', 'updated_by'], 'integer'],
            [['amount'], 'number'],
            [['created_at', 'updated_at', 'options', 'date', 'booking_slot', 'invoice', 'invoice_date','no_adults','no_children','coupon_code','coupon_price'], 'safe'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductsServices::className(), 'targetAttribute' => ['product_id' => 'id']],
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
            'user_id' => 'User ID',
            'product_id' => 'Item',
            'merchant_id' => 'Merchant ID',
            'quantity' => 'Quantity',
            'amount' => 'Amount',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct() {
        return $this->hasOne(ProductsServices::className(), ['id' => 'product_id']);
    }

    /**
     * Gets query for [[Merchant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant() {
        return $this->hasOne(Merchant::className(), ['id' => 'merchant_id']);
    }

    public function getOrderStatus() {
        return $this->hasOne(OrderStatus::className(), ['id' => 'status']);
    }

    public function getAttr($id) {
        return ProductAttributesValue::findOne(['id' => $id]);
    }

}
