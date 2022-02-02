<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "visa_requests".
 *
 * @property int $id
 * @property int $visa_option
 * @property int $user_id
 * @property int $processing_type
 * @property int|null $no_visa
 * @property string|null $travel_date_from
 * @property string|null $travel_date_to
 * @property int|null $status
 * @property int|null $visa_id
 *
 * @property VisaOption $visaOption
 * @property User $user
 * @property ProcessingType $processingType
 * @property Visa $visa
 */
class VisaRequests extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visa_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visa_option', 'user_id', 'processing_type'], 'required'],
            [['visa_option', 'user_id', 'processing_type', 'no_visa', 'status', 'visa_id'], 'integer'],
            [['travel_date_from', 'travel_date_to'], 'safe'],
            [['visa_option'], 'exist', 'skipOnError' => true, 'targetClass' => VisaOption::className(), 'targetAttribute' => ['visa_option' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['processing_type'], 'exist', 'skipOnError' => true, 'targetClass' => ProcessingType::className(), 'targetAttribute' => ['processing_type' => 'id']],
            [['visa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Visa::className(), 'targetAttribute' => ['visa_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visa_option' => 'Visa Option',
            'user_id' => 'User ID',
            'processing_type' => 'Processing Type',
            'no_visa' => 'No Visa',
            'travel_date_from' => 'Travel Date From',
            'travel_date_to' => 'Travel Date To',
            'status' => 'Status',
            'visa_id' => 'Visa ID',
        ];
    }

    /**
     * Gets query for [[VisaOption]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisaOption()
    {
        return $this->hasOne(VisaOption::className(), ['id' => 'visa_option']);
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

    /**
     * Gets query for [[ProcessingType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcessingType()
    {
        return $this->hasOne(ProcessingType::className(), ['id' => 'processing_type']);
    }

    /**
     * Gets query for [[Visa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisa()
    {
        return $this->hasOne(Visa::className(), ['id' => 'visa_id']);
    }
}
