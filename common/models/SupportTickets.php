<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "support_tickets".
 *
 * @property string $id
 * @property int|null $user_id
 * @property int|null $admin_id
 * @property int $status 1-Pending,2-Open/Accepted,3-Closed,4-Forwareded
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_by_type 1-User,2-Admin
 * @property int $updated_by_type 1-User,2-Admin
 * @property int|null $sort_order
 *
 * @property SupportChat[] $supportChats
 */
class SupportTickets extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'support_tickets';
    }

    public $message;
    public $file;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'order_id', 'product_id'], 'required'],
            [['user_id', 'admin_id', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'sort_order'], 'integer'],
            [['created_at', 'updated_at', 'file', 'message'], 'safe'],
            [['id'], 'string', 'max' => 50],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'admin_id' => 'Admin ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'sort_order' => 'Sort Order',
        ];
    }

    /**
     * Gets query for [[SupportChats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupportChats() {
        return $this->hasMany(SupportChat::className(), ['ticket_id' => 'id']);
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getAdmin() {
        return $this->hasOne(UserAdmin::className(), ['id' => 'admin_id']);
    }

    public function getOrderProduct() {
        return $this->hasOne(OrderProducts::className(), ['id' => 'product_id']);
    }

    public function getOrder() {
        return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }

}
