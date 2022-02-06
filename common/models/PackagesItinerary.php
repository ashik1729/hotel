<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "packages_itinerary".
 *
 * @property int $id
 * @property int $package_id
 * @property string|null $title
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 */
class PackagesItinerary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'packages_itinerary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['package_id','title','description', 'created_at', 'updated_at'], 'required'],
            [['package_id'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 250],
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
            'title' => 'Title',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
