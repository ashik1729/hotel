<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "booking_travellers".
 *
 * @property int $id
 * @property int|null $cart_id
 * @property int|null $order_product_id
 * @property string $first_name
 * @property string $last_name
 * @property string $created_at
 * @property int $status
 * @property int|null $user_id
 *
 * @property Cart $cart
 * @property OrderProducts $orderProduct
 * @property User $user
 */
class BookingTravellers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'booking_travellers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cart_id', 'order_product_id', 'status', 'user_id'], 'integer'],
            [['first_name', 'last_name'], 'required'],
            [['created_at'], 'safe'],
            [['first_name', 'last_name'], 'string', 'max' => 200],
            [['cart_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::className(), 'targetAttribute' => ['cart_id' => 'id']],
            [['order_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderProducts::className(), 'targetAttribute' => ['order_product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cart_id' => 'Cart ID',
            'order_product_id' => 'Order Product ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'created_at' => 'Created At',
            'status' => 'Status',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Cart]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCart()
    {
        return $this->hasOne(Cart::className(), ['id' => 'cart_id']);
    }

    /**
     * Gets query for [[OrderProduct]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProduct()
    {
        return $this->hasOne(OrderProducts::className(), ['id' => 'order_product_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
