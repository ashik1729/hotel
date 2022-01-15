<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "periods".
 *
 * @property int $id
 * @property int $month
 * @property int $status
 * @property string $created_at
 * @property int $created_by
 * @property int $created_by_type
 * @property string $updated_at
 * @property int $updated_by
 * @property int $updated_by_type
 *
 * @property Plans[] $plans
 */
class Periods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['month', 'status', 'created_at', 'created_by', 'created_by_type', 'updated_at', 'updated_by', 'updated_by_type'], 'required'],
            [['month', 'status', 'created_by', 'created_by_type', 'updated_by', 'updated_by_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'month' => 'Month',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'created_by_type' => 'Created By Type',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'updated_by_type' => 'Updated By Type',
        ];
    }

    /**
     * Gets query for [[Plans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlans()
    {
        return $this->hasMany(Plans::className(), ['period_id' => 'id']);
    }
}
