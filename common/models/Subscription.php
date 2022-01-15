<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subscription".
 *
 * @property int $id
 * @property int $plan_id
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $updated_by
 * @property int $created_by
 * @property int $created_by_type
 * @property int $updated_by_type
 * @property int $status
 * @property string $start_date
 * @property string $end_date
 *
 * @property Plans $plan
 * @property User $user
 */
class Subscription extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'subscription';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['plan_id', 'user_id', 'created_at', 'updated_at', 'updated_by', 'created_by', 'created_by_type', 'updated_by_type', 'status', 'start_date', 'end_date'], 'required'],
            [['plan_id', 'user_id', 'updated_by', 'created_by', 'created_by_type', 'updated_by_type', 'status'], 'integer'],
            [['created_at', 'updated_at', 'start_date', 'end_date'], 'safe'],
            [['plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Plans::className(), 'targetAttribute' => ['plan_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'plan_id' => 'Plan ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_by' => 'Created By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'status' => 'Status',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
    }

    /**
     * Gets query for [[Plan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlan() {
        return $this->hasOne(Plans::className(), ['id' => 'plan_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant() {
        return $this->hasOne(Merchant::className(), ['id' => 'merchant_id']);
    }

}
