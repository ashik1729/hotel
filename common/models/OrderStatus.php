<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_status".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $status
 * @property int $sort_order
 *
 * @property OrderHistory[] $orderHistories
 */
class OrderStatus extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'order_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'created_by', 'updated_by', 'name_ar'], 'required'],
            [['created_by', 'updated_by', 'status', 'sort_order'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'description_ar'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'status' => 'Status',
            'sort_order' => 'Sort Order',
        ];
    }

    /**
     * Gets query for [[OrderHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderHistories() {
        return $this->hasMany(OrderHistory::className(), ['order_status' => 'id']);
    }

}
