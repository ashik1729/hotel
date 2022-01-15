<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_api_settings".
 *
 * @property int $id
 * @property string $mobile_string
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $version
 */
class ApiSettings extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'api_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['mobile_string', 'status', 'version'], 'required'],
            [['status', 'version'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['mobile_string'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'mobile_string' => 'Mobile String',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'version' => 'Version',
        ];
    }

}
