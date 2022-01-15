<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "language".
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
 * @property LanguageData[] $languageDatas
 * @property SystemConfiguration[] $systemConfigurations
 */
class Language extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'language';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'shortcode', 'status', 'created_by', 'created_by_type'], 'required'],
            [['status', 'created_by', 'created_by_type'], 'integer'],
            [['created_at', 'image'], 'safe'],
            [['name', 'image'], 'string', 'max' => 100],
            [['shortcode'], 'string', 'max' => 3],
            [['shortcode'], 'unique'],
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
     * Gets query for [[LanguageDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageDatas() {
        return $this->hasMany(LanguageData::className(), ['lang_id' => 'id']);
    }

    /**
     * Gets query for [[SystemConfigurations]].
     *
     * @return \yii\db\ActiveQuery
     */
//    public function getSystemConfigurations() {
//        return $this->hasMany(SystemConfiguration::className(), ['language' => 'id']);
//    }
}
