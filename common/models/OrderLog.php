<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_log".
 *
 * @property string $id
 * @property int $order_id
 * @property int $user_id
 * @property int $status
 * @property string $log
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by_type
 * @property int $updated_by_type
 * @property int $store
 */
class OrderLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'user_id', 'status', 'log', 'created_by_type', 'updated_by_type', 'store'], 'required'],
            [['order_id', 'user_id', 'status', 'created_by_type', 'updated_by_type', 'store'], 'integer'],
            [['log'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['id'], 'string', 'max' => 100],
            [['id'], 'unique'],
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
            'user_id' => 'User ID',
            'status' => 'Status',
            'log' => 'Log',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'store' => 'Store',
        ];
    }
}
