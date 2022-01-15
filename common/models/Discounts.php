<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "discounts".
 *
 * @property int $id
 * @property string $title
 * @property string $title_ar
 * @property string $description
 * @property string $description_ar
 * @property int $discount_type
 * @property int $discount_rate
 * @property string|null $discount_from
 * @property string|null $discount_to
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_by_type
 * @property int $updated_by_type
 * @property int $status
 * @property int|null $sort_order
 */
class Discounts extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'discounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['title', 'title_ar', 'description', 'description_ar', 'discount_type', 'item_type', 'discount_rate', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'status'], 'required'],
            [['description', 'description_ar'], 'string'],
            [['discount_type', 'discount_rate', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'status', 'sort_order'], 'integer'],
            [['discount_from', 'discount_to', 'created_at', 'updated_at', 'merchant_id', 'coupon_code'], 'safe'],
            [['title', 'title_ar'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'title_ar' => 'Title Ar',
            'description' => 'Description',
            'description_ar' => 'Description Ar',
            'discount_type' => 'Discount Type',
            'discount_rate' => 'Discount Rate',
            'discount_from' => 'Discount From',
            'discount_to' => 'Discount To',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
            'status' => 'Status',
            'sort_order' => 'Sort Order',
        ];
    }

    public function getMerchant() {
        return $this->hasOne(Merchant::className(), ['id' => 'merchant_id']);
    }

}
