<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rental_enquiry".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property int|null $driver_staus 1-With Driver,2-Without Driver
 * @property string $date_from
 * @property string $date_to
 * @property string|null $message
 * @property int $car_id
 * @property int|null $status
 *
 * @property Cars $car
 */
class RentalEnquiry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rental_enquiry';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'date_from', 'date_to', 'car_id'], 'required'],
            [['driver_staus', 'car_id', 'status'], 'integer'],
            [['date_from', 'date_to'], 'safe'],
            [['message'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 15],
            [['car_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cars::className(), 'targetAttribute' => ['car_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'driver_staus' => 'Driver Staus',
            'date_from' => 'Date From',
            'date_to' => 'Date To',
            'message' => 'Message',
            'car_id' => 'Car ID',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Car]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCar()
    {
        return $this->hasOne(Cars::className(), ['id' => 'car_id']);
    }
}
