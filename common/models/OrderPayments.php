<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_payments".
 *
 * @property int $id
 * @property int $order_id
 * @property float $pay_amount
 * @property int $pay_type 1-cash,2-card,3-Online
 * @property string|null $transaction_id
 * @property string|null $comment
 * @property int $payment_status 0-Pending,1-Success,2-Failed
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_by_type
 * @property int $updated_by_type
 * @property int $status
 */
class OrderPayments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'pay_amount', 'pay_type', 'payment_status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'status'], 'required'],
            [['order_id', 'pay_type', 'payment_status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'status'], 'integer'],
            [['pay_amount'], 'number'],
            [['comment'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['transaction_id'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'pay_amount' => 'Pay Amount',
            'pay_type' => 'Pay Type',
            'transaction_id' => 'Transaction ID',
            'comment' => 'Comment',
            'payment_status' => 'Payment Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'status' => 'Status',
        ];
    }
}
