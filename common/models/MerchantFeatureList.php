<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "merchant_feature_list".
 *
 * @property int $id
 * @property int $merchant_id
 * @property string $value_en
 * @property string $value_ar
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_by_type
 * @property int $updated_by_type
 * @property int $sort_order
 *
 * @property Merchant $merchant
 */
class MerchantFeatureList extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'merchant_feature_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['merchant_id', 'value_en', 'value_ar', 'status', 'feature_id', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'sort_order'], 'required'],
            [['merchant_id', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'sort_order'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['value_en', 'value_ar'], 'string', 'max' => 255],
            [['merchant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Merchant::className(), 'targetAttribute' => ['merchant_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'merchant_id' => 'Merchant ID',
            'value_en' => 'Value En',
            'value_ar' => 'Value Ar',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'sort_order' => 'Sort Order',
        ];
    }

    /**
     * Gets query for [[Merchant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant() {
        return $this->hasOne(Merchant::className(), ['id' => 'merchant_id']);
    }

    public function getFeatureList() {
        return $this->hasOne(FeaturesList::className(), ['id' => 'feature_id']);
    }

}
