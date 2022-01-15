<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $email
 * @property string $additional_email
 * @property string $phone_number
 * @property string $additional_phone_number
 * @property string $address
 * @property string $logo
 * @property string $favicon
 * @property int $status
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'additional_email', 'phone_number', 'additional_phone_number', 'address', 'logo', 'favicon', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['address'], 'string'],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['email', 'logo', 'favicon'], 'string', 'max' => 100],
            [['additional_email'], 'string', 'max' => 200],
            [['phone_number', 'additional_phone_number'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'additional_email' => 'Additional Email',
            'phone_number' => 'Phone Number',
            'additional_phone_number' => 'Additional Phone Number',
            'address' => 'Address',
            'logo' => 'Logo',
            'favicon' => 'Favicon',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
