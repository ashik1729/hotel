<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $user_id
 * @property string $transaction_id
 * @property int $shipping_method
 * @property int $ship_address
 * @property int $bill_address
 * @property string $customer_comment
 * @property string $admin_comment
 * @property float $total_amount
 * @property int $payment_method
 * @property int $payment_status
 * @property int $status
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property float $shipping_charge
 *
 * @property OrderHistory[] $orderHistories
 * @property OrderProducts[] $orderProducts
 * @property User $user
 * @property UserAddress $shipAddress
 * @property UserAddress $billAddress
 */
class Orders extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'ship_address', 'bill_address', 'total_amount', 'payment_method', 'payment_status', 'status', 'created_by', 'updated_by', 'shipping_charge', 'created_by_type', 'updated_by_type'], 'required'],
            [['user_id', 'ship_address', 'bill_address', 'total_amount', 'payment_method', 'payment_status', 'status', 'created_by', 'updated_by', 'shipping_charge', 'created_by_type', 'updated_by_type'], 'required', 'on' => 'create_order_backend'],
            [['user_id', 'ship_address', 'bill_address', 'payment_method', 'payment_status', 'status', 'created_by', 'updated_by'], 'integer'],
            [['customer_comment', 'admin_comment'], 'string'],
            [['total_amount', 'shipping_charge'], 'number'],
            [['created_at', 'updated_at', 'transaction_id', 'amount_paid'], 'safe'],
            [['transaction_id'], 'string', 'max' => 100],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['ship_address'], 'exist', 'skipOnError' => true, 'targetClass' => UserAddress::className(), 'targetAttribute' => ['ship_address' => 'id']],
            [['bill_address'], 'exist', 'skipOnError' => true, 'targetClass' => UserAddress::className(), 'targetAttribute' => ['bill_address' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'Order ID',
            'user_id' => 'User',
            'transaction_id' => 'Transaction ID',
            'shipping_method' => 'Shipping Method',
            'ship_address' => 'Ship Address',
            'bill_address' => 'Bill Address',
            'customer_comment' => 'Customer Comment',
            'admin_comment' => 'Admin Comment',
            'total_amount' => 'Total Amount',
            'payment_method' => 'Payment Method',
            'payment_status' => 'Payment Status',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'shipping_charge' => 'Shipping Charge',
        ];
    }

    /**
     * Gets query for [[OrderHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderHistories() {
        return $this->hasMany(OrderHistory::className(), ['order_id' => 'id']);
    }

    /**
     * Gets query for [[OrderProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts() {
        return $this->hasMany(OrderProducts::className(), ['order_id' => 'id']);
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
     * Gets query for [[ShipAddress]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShipAddress() {
        return $this->hasOne(UserAddress::className(), ['id' => 'ship_address']);
    }

    public function getStore0() {
        return $this->hasOne(Franchise::className(), ['id' => 'store']);
    }

    /**
     * Gets query for [[BillAddress]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBillAddress() {
        return $this->hasOne(UserAddress::className(), ['id' => 'bill_address']);
    }

    public function getOrderStatus() {
        return $this->hasOne(OrderStatus::className(), ['id' => 'status']);
    }

}
