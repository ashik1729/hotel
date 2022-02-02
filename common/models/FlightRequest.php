<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "flight_request".
 *
 * @property int $id
 * @property string $from_place
 * @property string $checkin_date
 * @property string|null $return_date
 * @property int $no_adult
 * @property int $no_children
 * @property int $no_room
 * @property string|null $class
 * @property int $purpose 1-Tourism,2-Work
 * @property int|null $status
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string|null $to_place
 */
class FlightRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'flight_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from_place', 'checkin_date', 'no_adult', 'no_children',  'name', 'email', 'phone'], 'required'],
            [['checkin_date', 'return_date'], 'safe'],
            [['no_adult', 'no_children', 'no_room', 'purpose', 'status'], 'integer'],
            [['from_place', 'to_place'], 'string', 'max' => 200],
            [['class', 'name', 'email'], 'string', 'max' => 100],
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
            'from_place' => 'From Place',
            'checkin_date' => 'Checkin Date',
            'return_date' => 'Return Date',
            'no_adult' => 'No Adult',
            'no_children' => 'No Children',
            'no_room' => 'No Room',
            'class' => 'Class',
            'purpose' => 'Purpose',
            'status' => 'Status',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'to_place' => 'To Place',
        ];
    }
}
