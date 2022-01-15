<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "language_data".
 *
 * @property int $id
 * @property int $type
 * @property int $lang_id
 * @property int $item_id
 * @property string $value
 * @property string $created_at
 * @property int $created_by
 * @property int $created_by_type
 * @property string $updated_at
 * @property int $updated_by
 * @property int $updated_by_type
 * @property int $status
 *
 * @property Language $lang
 */
class LanguageData extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'language_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['type', 'lang_id', 'item_id', 'value', 'status'], 'required'],
            [['type', 'lang_id', 'item_id', 'created_by', 'created_by_type', 'updated_by', 'updated_by_type', 'status'], 'integer'],
            [['value'], 'string'],
            [['created_at', 'updated_at', 'created_at', 'created_by', 'created_by_type', 'updated_at', 'updated_by', 'updated_by_type'], 'safe'],
            [['lang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['lang_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'lang_id' => 'Lang ID',
            'item_id' => 'Item ID',
            'value' => 'Value',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'created_by_type' => 'Created By Type',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'updated_by_type' => 'Updated By Type',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Lang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLang() {
        return $this->hasOne(Language::className(), ['id' => 'lang_id']);
    }

}
