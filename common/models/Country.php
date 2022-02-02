<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property string $country_name
 * @property string $iso
 * @property string $created_at
 * @property string $updated_at
 * @property int $status
 * @property int $id
 * @property string $iso3
 * @property int $numcode
 * @property int $phonecode
 * @property string $country_name_ar
 *
 * @property Franchise[] $franchises
 * @property States[] $states
 * @property User[] $users
 * @property UserAddress[] $userAddresses
 * @property Vendor[] $vendors
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_name', 'iso', 'iso3', 'numcode', 'phonecode', 'country_name_ar'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status', 'numcode', 'phonecode'], 'integer'],
            [['country_name', 'country_name_ar'], 'string', 'max' => 255],
            [['iso'], 'string', 'max' => 3],
            [['iso3'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'country_name' => 'Country Name',
            'iso' => 'Iso',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'id' => 'ID',
            'iso3' => 'Iso3',
            'numcode' => 'Numcode',
            'phonecode' => 'Phonecode',
            'country_name_ar' => 'Country Name Ar',
        ];
    }

    /**
     * Gets query for [[Franchises]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFranchises()
    {
        return $this->hasMany(Franchise::className(), ['country' => 'id']);
    }

    /**
     * Gets query for [[States]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStates()
    {
        return $this->hasMany(States::className(), ['country_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['country' => 'id']);
    }

    /**
     * Gets query for [[UserAddresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserAddresses()
    {
        return $this->hasMany(UserAddress::className(), ['country' => 'id']);
    }

    /**
     * Gets query for [[Vendors]].
     *
     * @return \yii\db\ActiveQuery
     */
   
}
