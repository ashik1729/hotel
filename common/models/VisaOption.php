<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "visa_option".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $status
 * @property int|null $sort_order
 */
class VisaOption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visa_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'sort_order'], 'integer'],
            [['title'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'status' => 'Status',
            'sort_order' => 'Sort Order',
        ];
    }
}
