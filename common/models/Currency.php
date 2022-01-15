<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "currency".
 *
 * @property int $id
 * @property string $name
 * @property string $shortcode
 * @property int $status
 * @property string $created_at
 * @property int $created_by
 * @property int $created_by_type
 * @property string $image
 *
 * @property SystemConfiguration[] $systemConfigurations
 */
class Currency extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'shortcode', 'shortcode_ar', 'status', 'created_by', 'created_by_type', 'image', 'value', 'position'], 'required'],
            [['status', 'created_by', 'created_by_type'], 'integer'],
            [['created_at'], 'safe'],
            [['shortcode'], 'string', 'max' => 3],
            [['shortcode'], 'unique'],
            [['name', 'image'], 'string', 'max' => 100],
            [['shortcode'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'shortcode' => 'Shortcode',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'created_by_type' => 'Created By Type',
            'image' => 'Image',
        ];
    }

    public function uploadFile($file, $name, $folder) {

        $targetFolder = \yii::$app->basePath . '/../uploads/' . $folder . '/';
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }
        if ($file->saveAs($targetFolder . $name . '.' . $file->extension)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gets query for [[SystemConfigurations]].
     *
     * @return \yii\db\ActiveQuery
     */
//    public function getSystemConfigurations() {
//        return $this->hasMany(SystemConfiguration::className(), ['currency' => 'id']);
//    }
}
