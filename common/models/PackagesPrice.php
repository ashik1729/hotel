<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "packages_price".
 *
 * @property int $id
 * @property int $package_id
 * @property string $package_date_id
 * @property int|null $min_person[]
 * @property int|null $max_person
 * @property float|null $price
 * @property string $created_at
 * @property string $updated_at
 */
class PackagesPrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'packages_price';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['package_id', 'package_date_id', 'created_at', 'updated_at','min_person','max_person','price'], 'required'],
            [['package_id', 'min_person', 'max_person'], 'integer'],
            [['package_date_id', 'created_at', 'updated_at','package_id','id'], 'safe'],
            [['price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'package_id' => 'Package ID',
            'package_date_id' => 'Package Date ID',
            'min_person' => 'Min Person',
            'max_person' => 'Max Person',
            'price' => 'Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
