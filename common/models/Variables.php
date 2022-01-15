<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_global_variables".
 *
 * @property int $id
 * @property string $label
 * @property string $key_name
 * @property string $key_value
 * @property string $created_at
 * @property string $updated_at
 * @property int $status
 */
class Variables extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'variables';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['label', 'key_name', 'key_value', 'status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['label', 'key_name'], 'string', 'max' => 120],
            [['key_value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'label' => 'Label',
            'key_name' => 'Key Name',
            'key_value' => 'Key Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

}
