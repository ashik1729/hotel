<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "week_days_availability".
 *
 * @property int $id
 * @property string|null $day
 * @property int $merchant_id
 * @property string $available_from
 * @property string $available_to
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_by_type
 * @property int|null $updated_by_type
 * @property string|null $date
 * @property int $slot_interval
 * @property int|null $availability
 *
 * @property Merchant $merchant
 */
class WeekDaysAvailability extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'week_days_availability';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['day'], 'string'],
            [['merchant_id', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'slot_interval', 'availability'], 'integer'],
            [['available_from', 'available_to', 'created_at', 'updated_at', 'date', 'merchant_id', 'available_from', 'available_to', 'status', 'slot_interval'], 'safe'],
            [['merchant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Merchant::className(), 'targetAttribute' => ['merchant_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'day' => 'Day',
            'merchant_id' => 'Merchant ID',
            'available_from' => 'Available From',
            'available_to' => 'Available To',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'date' => 'Date',
            'slot_interval' => 'Slot Interval',
            'availability' => 'Availability',
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
