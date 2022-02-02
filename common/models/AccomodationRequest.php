<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "accomodation_request".
 *
 * @property int $id
 * @property string $destination
 * @property string $checkin_date
 * @property float $checkout_date
 * @property int $no_adult
 * @property int $no_children
 * @property int $no_room
 * @property int $accomodation
 * @property int $purpose
 * @property int|null $status
 * @property string $name
 * @property string $email
 * @property string $phone
 */
class AccomodationRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'accomodation_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['destination', 'checkin_date', 'checkout_date', 'no_adult', 'no_children', 'no_room', 'accomodation', 'purpose', 'name', 'email', 'phone'], 'required'],
            [['checkin_date'], 'safe'],
            [['no_adult', 'no_children', 'no_room', 'accomodation', 'purpose', 'status'], 'integer'],
            [['destination'], 'string', 'max' => 200],
            [['name', 'email'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'destination' => 'Destination',
            'checkin_date' => 'Checkin Date',
            'checkout_date' => 'Checkout Date',
            'no_adult' => 'No Adult',
            'no_children' => 'No Children',
            'no_room' => 'No Room',
            'accomodation' => 'Accomodation',
            'purpose' => 'Purpose',
            'status' => 'Status',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
        ];
    }
    public function getAccomodation0()
    {
        return $this->hasOne(Accomodation::className(), ['id' => 'accomodation']);
    }
}
