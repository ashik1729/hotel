<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_mobile_strings".
 *
 * @property int $id
 * @property string $module
 * @property string $string_en
 * @property string $string_ar
 * @property int $status
 * @property double $version
 * @property string $created_at
 * @property string $updated_at
 */
class MobileStrings extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public $import;

    public static function tableName() {
        return 'mobile_strings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['module', 'string_en', 'string_ar', 'status', 'version', 'string_key'], 'required'],
            [['status'], 'integer'],
            [['version'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['import'], 'required', 'on' => 'import'],
            [['module'], 'string', 'max' => 100],
            [['string_key'], 'unique'],
                // [['string_en', 'string_ar'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'module' => 'Module',
            'string_en' => 'String En',
            'string_ar' => 'String Ar',
            'string_key' => 'Key',
            'status' => 'Status',
            'version' => 'Version',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function uploadFile($file, $id) {

        $targetFolder = \yii::$app->basePath . '/../uploads/local-string/';
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }
        if ($file->saveAs($targetFolder . 'import' . '.' . $file->extension)) {
            return true;
        } else {
            return false;
        }
    }

}
