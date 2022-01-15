<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "disable_slots".
 *
 * @property int $id
 * @property string|null $date
 * @property string|null $slot_from
 * @property string|null $slot_to
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_by_type
 * @property int|null $updated_by_type
 * @property int $merchant_id
 *
 * @property Merchant $merchant
 */
class DisableSlots extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'disable_slots';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['date', 'slot_from', 'slot_to', 'status', 'created_at', 'updated_at', 'merchant_id', 'day'], 'safe'],
            [['status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'merchant_id'], 'integer'],
            [['merchant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Merchant::className(), 'targetAttribute' => ['merchant_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'slot_from' => 'Slot From',
            'slot_to' => 'Slot To',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'merchant_id' => 'Merchant ID',
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

}
