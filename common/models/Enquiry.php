<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "enquiry".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string|null $message
 * @property int $status
 */
class Enquiry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'enquiry';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'phone'], 'required'],
            [['message'], 'string'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'message' => 'Message',
            'status' => 'Status',
        ];
    }
}
