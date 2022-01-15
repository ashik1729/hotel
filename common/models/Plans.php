<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "plans".
 *
 * @property int $id
 * @property string $name
 * @property int $period_id
 * @property int $no_of_products
 * @property string $created_at
 * @property int $created_by
 * @property int $created_by_type
 * @property int $updated_by
 * @property string $updated_at
 * @property int $updated_by_type
 * @property int $status
 *
 * @property Periods $period
 * @property Subscription[] $subscriptions
 */
class Plans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plans';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'period_id', 'no_of_products', 'created_at', 'created_by', 'created_by_type', 'updated_by', 'updated_at', 'updated_by_type', 'status'], 'required'],
            [['period_id', 'no_of_products', 'created_by', 'created_by_type', 'updated_by', 'updated_by_type', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['period_id'], 'exist', 'skipOnError' => true, 'targetClass' => Periods::className(), 'targetAttribute' => ['period_id' => 'id']],
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
            'period_id' => 'Period ID',
            'no_of_products' => 'No Of Products',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'created_by_type' => 'Created By Type',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'updated_by_type' => 'Updated By Type',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Period]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriod()
    {
        return $this->hasOne(Periods::className(), ['id' => 'period_id']);
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions()
    {
        return $this->hasMany(Subscription::className(), ['plan_id' => 'id']);
    }
}
