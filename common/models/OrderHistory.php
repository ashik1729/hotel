<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_history".
 *
 * @property int $id
 * @property int $order_id
 * @property int $order_product_id
 * @property string $tracking_id
 * @property string $order_status_custome_comment
 * @property int $order_status
 * @property int|null $shipping_type
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $updated_by
 * @property int $created_by
 *
 * @property Orders $order
 * @property ProductsServices $product
 * @property OrderStatus $orderStatus
 */
class OrderHistory extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'order_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['order_id', 'order_product_id', 'order_status', 'status', 'updated_by', 'created_by', 'created_by_type', 'updated_by_type'], 'required'],
            [['order_id', 'order_product_id', 'order_status', 'shipping_type', 'status', 'updated_by', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['tracking_id'], 'string', 'max' => 50],
            [['order_status_custome_comment'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['order_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderProducts::className(), 'targetAttribute' => ['order_product_id' => 'id']],
            [['order_status'], 'exist', 'skipOnError' => true, 'targetClass' => OrderStatus::className(), 'targetAttribute' => ['order_status' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'order_product_id' => 'Order Product ID',
            'tracking_id' => 'Tracking ID',
            'order_status_custome_comment' => 'Order Comment',
            'order_status' => 'Order Status',
            'shipping_type' => 'Shipping Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_by' => 'Created By',
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
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProduct() {
        return $this->hasOne(OrderProducts::className(), ['id' => 'order_product_id']);
    }

    /**
     * Gets query for [[OrderStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderStatus() {
        return $this->hasOne(OrderStatus::className(), ['id' => 'order_status']);
    }

}
