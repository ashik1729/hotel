<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "merchant_shipment_methods".
 *
 * @property int $id
 * @property int $merchant_id
 * @property int $shippment_id
 * @property float|null $price
 * @property string|null $information
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_by_type
 * @property int|null $updated_by_type
 * @property int|null $defaultShipment
 *
 * @property ShipmentMethods $shippment
 * @property Merchant $merchant
 */
class MerchantShipmentMethods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_shipment_methods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'shippment_id'], 'required'],
            [['merchant_id', 'shippment_id', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'defaultShipment'], 'integer'],
            [['price'], 'number'],
            [['information'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['shippment_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShipmentMethods::className(), 'targetAttribute' => ['shippment_id' => 'id']],
            [['merchant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Merchant::className(), 'targetAttribute' => ['merchant_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => 'Merchant ID',
            'shippment_id' => 'Shippment ID',
            'price' => 'Price',
            'information' => 'Information',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'defaultShipment' => 'Default Shipment',
        ];
    }

    /**
     * Gets query for [[Shippment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShippment()
    {
        return $this->hasOne(ShipmentMethods::className(), ['id' => 'shippment_id']);
    }

    /**
     * Gets query for [[Merchant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(Merchant::className(), ['id' => 'merchant_id']);
    }
}
