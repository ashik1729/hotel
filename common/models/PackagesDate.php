<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "packages_date".
 *
 * @property int $id
 * @property int $package_id
 * @property string $package_date
 * @property int|null $package_quantity
 * @property string|null $created_date
 * @property string|null $updated_date
 */
class PackagesDate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'packages_date';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['package_id', 'package_date','package_quantity'], 'required'],
            [['package_id', 'package_quantity'], 'integer'],
            [['package_date', 'created_date', 'updated_date'], 'safe'],
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
            'package_date' => 'Package Date',
            'package_quantity' => 'Package Quantity',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
        ];
    }
}
