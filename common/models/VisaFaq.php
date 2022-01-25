<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "visa_faq".
 *
 * @property int $id
 * @property string|null $question
 * @property string|null $answer
 * @property int|null $status
 * @property int|null $sort_order
 * @property int|null $visa_id
 *
 * @property Visa $visa
 */
class VisaFaq extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visa_faq';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question', 'answer'], 'string'],
            [['status', 'sort_order', 'visa_id'], 'integer'],
            [['visa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Visa::className(), 'targetAttribute' => ['visa_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question' => 'Question',
            'answer' => 'Answer',
            'status' => 'Status',
            'sort_order' => 'Sort Order',
            'visa_id' => 'Visa ID',
        ];
    }

    /**
     * Gets query for [[Visa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisa()
    {
        return $this->hasOne(Visa::className(), ['id' => 'visa_id']);
    }
}
