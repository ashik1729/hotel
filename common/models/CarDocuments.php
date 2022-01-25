<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "car_documents".
 *
 * @property int $id
 * @property int $car_id
 * @property int|null $ref_id
 * @property int|null $status
 *
 * @property Cars $car
 * @property DocumentsMaster $ref
 */
class CarDocuments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car_documents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['car_id'], 'required'],
            [['car_id', 'ref_id', 'status'], 'integer'],
            [['car_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cars::className(), 'targetAttribute' => ['car_id' => 'id']],
            [['ref_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentsMaster::className(), 'targetAttribute' => ['ref_id' => 'id']],
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
            'ref_id' => 'Ref ID',
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

    /**
     * Gets query for [[Ref]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRef()
    {
        return $this->hasOne(DocumentsMaster::className(), ['id' => 'ref_id']);
    }
}
