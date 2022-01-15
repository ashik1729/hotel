<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "promotional_campaign".
 *
 * @property int $id
 * @property string $name
 * @property int $no_days
 * @property float $price
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Banner[] $banners
 */
class PromotionalCampaign extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotional_campaign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_days', 'status', 'created_by', 'updated_by'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
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
            'no_days' => 'No Days',
            'price' => 'Price',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Banners]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBanners()
    {
        return $this->hasMany(Banner::className(), ['promotion_id' => 'id']);
    }
}
