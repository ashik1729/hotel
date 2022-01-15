<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "import_items".
 *
 * @property int $id
 * @property int $merchant_id
 * @property int $status
 * @property int|null $file
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_by_type
 * @property int $updated_by_type
 */
class ImportItems extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'import_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['merchant_id', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type'], 'required'],
            [['merchant_id', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['file', 'merchant_id'], 'required', 'on' => 'import'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'merchant_id' => 'Merchant ID',
            'status' => 'Status',
            'file' => 'File',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_by_type' => 'Created By Type',
            'updated_by_type' => 'Updated By Type',
        ];
    }

    public function uploadFile($file, $id, $file_name) {

        $targetFolder = \yii::$app->basePath . '/../uploads/item-import/';
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }
        if ($file->saveAs($targetFolder . $file_name . '.' . $file->extension)) {
            return true;
        } else {
            return false;
        }
    }

}
