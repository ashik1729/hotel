<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_error_code".
 *
 * @property int $id
 * @property int $error_code
 * @property string $error_title
 * @property string $error_en
 * @property string $error_ar
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class ErrorCode extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public $import;
    public $image;
    public $property;

    public static function tableName() {
        return 'error_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['error_code', 'error_title', 'error_en', 'status'], 'required'],
            [['error_code', 'status'], 'integer'],
            [['error_en', 'error_ar'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['error_title'], 'string', 'max' => 255],
            [['error_code'], 'unique'],
            [['import'], 'required', 'on' => 'import'],
            [['property'], 'required', 'on' => 'property'],
            [['image'], 'required', 'on' => 'image'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'error_code' => 'Error Code',
            'error_title' => 'Error Title',
            'error_en' => 'Error En',
            'error_ar' => 'Error Ar',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function uploadFile($file, $id) {

        $targetFolder = \yii::$app->basePath . '/../uploads/';
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }
        if ($file->saveAs($targetFolder . 'import' . '.' . $file->extension)) {
            return true;
        } else {
            return false;
        }
    }

    public function uploadFilea($file, $id) {

        $targetFolder = \yii::$app->basePath . '/../uploads/';
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }
        if ($file->saveAs($targetFolder . 'property' . '.' . $file->extension)) {
            return true;
        } else {
            return false;
        }
    }

}
