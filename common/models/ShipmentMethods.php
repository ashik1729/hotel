<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shipment_methods".
 *
 * @property int $id
 * @property string|null $name_en
 * @property string|null $name_ar
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property MerchantShipmentMethods[] $merchantShipmentMethods
 */
class ShipmentMethods extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'shipment_methods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['name_en', 'name_ar'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name_en', 'name_ar'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name_en' => 'Name En',
            'name_ar' => 'Name Ar',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[MerchantShipmentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMerchantShipmentMethods() {
        return $this->hasMany(MerchantShipmentMethods::className(), ['shippment_id' => 'id']);
    }

}
