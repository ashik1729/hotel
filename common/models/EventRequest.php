<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "event_request".
 *
 * @property int $id
 * @property string $date
 * @property int $no_adult
 * @property int $event_id
 * @property int|null $status
 * @property string $name
 * @property string $email
 * @property string $phone
 *
 * @property Events $event
 */
class EventRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'no_adult', 'event_id', 'name', 'email', 'phone'], 'required'],
            [['date'], 'safe'],
            [['no_adult', 'event_id', 'status'], 'integer'],
            [['name', 'email'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 16],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Events::className(), 'targetAttribute' => ['event_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'no_adult' => 'No Adult',
            'event_id' => 'Event ID',
            'status' => 'Status',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
        ];
    }
    
    /**
     * Gets query for [[Event]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Events::className(), ['id' => 'event_id']);
    }
}
