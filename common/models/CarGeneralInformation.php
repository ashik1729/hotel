<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "car_general_information".
 *
 * @property int $id
 * @property int $car_id
 * @property string $value
 * @property int|null $status
 * @property int $ref_id
 *
 * @property Cars $car
 * @property GeneralInformationMaster $ref
 */
class CarGeneralInformation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car_general_information';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['car_id', 'value', 'ref_id'], 'required'],
            [['car_id', 'status', 'ref_id'], 'integer'],
            [['value'], 'string', 'max' => 200],
            [['car_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cars::className(), 'targetAttribute' => ['car_id' => 'id']],
            [['ref_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeneralInformationMaster::className(), 'targetAttribute' => ['ref_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_id' => 'Car ID',
            'value' => 'Value',
            'status' => 'Status',
            'ref_id' => 'Ref ID',
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

    /**
     * Gets query for [[Ref]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRef()
    {
        return $this->hasOne(GeneralInformationMaster::className(), ['id' => 'ref_id']);
    }
}
