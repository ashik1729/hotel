<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "system_configuration".
 *
 * @property int $id
 * @property int $language
 * @property int $currency
 *
 * @property Language $language0
 * @property Currency $currency0
 */
class SystemConfiguration extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'system_configuration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['language', 'currency'], 'required'],
//            [['language', 'currency'], 'integer'],
//            [['language'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language' => 'id']],
//            [['currency'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'language' => 'Language',
            'currency' => 'Currency',
        ];
    }

    /**
     * Gets query for [[Language0]].
     *
     * @return \yii\db\ActiveQuery
     */
//    public function getLanguage0()
//    {
//        return $this->hasOne(Language::className(), ['id' => 'language']);
//    }

    /**
     * Gets query for [[Currency0]].
     *
     * @return \yii\db\ActiveQuery
     */
//    public function getCurrency0()
//    {
//        return $this->hasOne(Currency::className(), ['id' => 'currency']);
//    }
}
