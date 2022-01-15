<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "states".
 *
 * @property int $id
 * @property int $country_id
 * @property string $state_name
 * @property int $status
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Franchise[] $franchises
 * @property Country $country
 * @property User[] $users
 * @property UserAddress[] $userAddresses
 * @property Vendor[] $vendors
 */
class States extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'states';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['country_id', 'state_name', 'status', 'created_by', 'updated_by'], 'required'],
            [['country_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at', 'state_name_ar'], 'safe'],
            [['state_name'], 'string', 'max' => 200],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'country_id' => 'Country ID',
            'state_name' => 'State Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[Franchises]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFranchises() {
        return $this->hasMany(Franchise::className(), ['state' => 'id']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry() {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers() {
        return $this->hasMany(User::className(), ['state' => 'id']);
    }

    /**
     * Gets query for [[UserAddresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserAddresses() {
        return $this->hasMany(UserAddress::className(), ['state' => 'id']);
    }

    /**
     * Gets query for [[Vendors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVendors() {
        return $this->hasMany(Vendor::className(), ['state' => 'id']);
    }

}
