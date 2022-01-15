<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_address".
 *
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property int $country
 * @property int $state
 * @property string $city
 * @property string $streat_address
 * @property string $postcode
 * @property string $phone_number
 * @property int $default_billing_address
 * @property int $default_shipping_address
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property string $email
 *
 * @property Orders[] $orders
 * @property Orders[] $orders0
 * @property User $user
 * @property Country $country0
 * @property States $state0
 */
class UserAddress extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'user_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'first_name', 'last_name', 'country', 'city', 'streat_address', 'default_billing_address', 'default_shipping_address'], 'required'],
            [['user_id', 'country', 'state', 'default_billing_address', 'default_shipping_address', 'created_by', 'updated_by'], 'integer'],
            [['streat_address'], 'string'],
            [['created_at', 'updated_at', 'created_by_type', 'updated_by_type', 'created_at', 'updated_at', 'postcode', 'created_by', 'updated_by', 'email', 'phone_number'], 'safe'],
            [['first_name', 'last_name'], 'string', 'max' => 200],
            [['email'], 'string', 'max' => 100],
            [['postcode', 'phone_number'], 'string', 'max' => 15],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['country'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country' => 'id']],
            [['state'], 'exist', 'skipOnError' => true, 'targetClass' => States::className(), 'targetAttribute' => ['state' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'streat_address' => 'Streat Address',
            'postcode' => 'Postcode',
            'phone_number' => 'Phone Number',
            'default_billing_address' => 'Default Billing Address',
            'default_shipping_address' => 'Default Shipping Address',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'email' => 'Email',
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders() {
        return $this->hasMany(Orders::className(), ['ship_address' => 'id']);
    }

    /**
     * Gets query for [[Orders0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders0() {
        return $this->hasMany(Orders::className(), ['bill_address' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Country0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry0() {
        return $this->hasOne(Country::className(), ['id' => 'country']);
    }

    /**
     * Gets query for [[State0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getState0() {
        return $this->hasOne(States::className(), ['id' => 'state']);
    }

    public function getCity0() {
        return $this->hasOne(City::className(), ['id' => 'city']);
    }

}
