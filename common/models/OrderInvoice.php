<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_invoice".
 *
 * @property int $id
 * @property int $order_id
 * @property int $merchant_id
 * @property string|null $invoice
 * @property string|null $invoice_date
 * @property string $invoice_file
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_by_type
 * @property int $updated_by_type
 * @property int|null $sent_to_customer
 * @property int|null $sent_to_customer_count
 *
 * @property Merchant $merchant
 * @property Orders $order
 */
class OrderInvoice extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'order_invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['order_id', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type'], 'required'],
            [['order_id', 'merchant_id', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'sent_to_customer', 'sent_to_customer_count'], 'integer'],
            [['invoice_date', 'created_at', 'updated_at'], 'safe'],
            [['invoice'], 'string', 'max' => 30],
            [['invoice_file'], 'string', 'max' => 100],
            [['merchant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Merchant::className(), 'targetAttribute' => ['merchant_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
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
            'invoice' => 'Invoice',
            'invoice_date' => 'Invoice Date',
            'invoice_file' => 'Invoice File',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'sent_to_customer' => 'Sent To Customer',
            'sent_to_customer_count' => 'Sent To Customer Count',
        ];
    }

    /**
     * Gets query for [[Merchant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant() {
        return $this->hasOne(Merchant::className(), ['id' => 'merchant_id']);
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder() {
        return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }

}
