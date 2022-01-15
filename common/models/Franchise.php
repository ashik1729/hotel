<?php

namespace common\models;

use yii\web\IdentityInterface;
use Yii;

/**
 * This is the model class for table "franchise".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property int $gender
 * @property string $dob
 * @property string $email
 * @property string $password
 * @property string|null $password_reset_token
 * @property string $profile_image
 * @property string|null $mobile_number
 * @property string|null $address
 * @property int|null $country
 * @property int $state
 * @property int|null $city
 * @property string|null $auth_key
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $status
 * @property int|null $newsletter
 * @property string|null $user_otp
 * @property int $emailverify
 * @property int $created_by
 * @property int $created_by_type
 * @property int $updated_by
 * @property int $updated_by_type
 * @property int $shipping_type
 * @property int $payment_type
 * @property string $alternative_email_address
 * @property string $shipping_terms
 *
 * @property Country $country0
 * @property States $state0
 * @property Merchant[] $merchants
 */
class Franchise extends \yii\db\ActiveRecord implements IdentityInterface {

    /**
     * {@inheritdoc}
     */
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public static function tableName() {
        return 'franchise';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['first_name', 'last_name', 'email', 'password', 'state', 'created_by_type', 'updated_by', 'updated_by_type', 'currency'], 'required'],
            [['gender', 'country', 'state', 'status', 'newsletter', 'emailverify', 'created_by', 'created_by_type', 'updated_by', 'updated_by_type', 'shipping_type', 'payment_type'], 'integer'],
            [['dob', 'gender', 'created_at', 'updated_at', 'shipping_type', 'payment_type', 'shipping_terms', 'newsletter', 'alternative_email_address', 'city', 'profile_image', 'invoice_prefix'], 'safe'],
            [['address', 'shipping_terms'], 'string'],
            [['first_name', 'last_name', 'password_reset_token', 'profile_image'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 120],
            [['password'], 'string', 'max' => 100],
            [['mobile_number'], 'string', 'max' => 15],
            [['auth_key', 'alternative_email_address'], 'string', 'max' => 255],
            [['user_otp'], 'string', 'max' => 12],
            [['email'], 'unique'],
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
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'gender' => 'Gender',
            'dob' => 'Dob',
            'email' => 'Email',
            'password' => 'Password',
            'password_reset_token' => 'Password Reset Token',
            'profile_image' => 'Profile Image',
            'mobile_number' => 'Mobile Number',
            'address' => 'Address',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'auth_key' => 'Auth Key',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'newsletter' => 'Newsletter',
            'user_otp' => 'User Otp',
            'emailverify' => 'Emailverify',
            'created_by' => 'Created By',
            'created_by_type' => 'Created By Type',
            'updated_by' => 'Updated By',
            'updated_by_type' => 'Updated By Type',
            'shipping_type' => 'Shipping Type',
            'payment_type' => 'Pament Type',
            'alternative_email_address' => 'Alternative Email Address',
            'shipping_terms' => 'Shipping Terms',
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
     * Gets query for [[Country0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry0() {
        return $this->hasOne(Country::className(), ['id' => 'country']);
    }

    public function getCurrency0() {
        return $this->hasOne(Currency::className(), ['id' => 'currency']);
    }

    /**
     * Gets query for [[State0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getState0() {
        return $this->hasOne(States::className(), ['id' => 'state']);
    }

    /**
     * Gets query for [[Merchants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMerchants() {
        return $this->hasMany(Merchant::className(), ['franchise_id' => 'id']);
    }

    public function getOrders() {
        return $this->hasMany(Orders::className(), ['store' => 'id']);
    }

    function getRandomPwd() {
        $psw = rand(10001, 9999999999);
//        $special_array[] = "@";
        $special_array[] = "$";
//        $special_array[] = "%";
//        $special_array[] = "^";
        $special_array[] = "&";
//        $special_array[] = "*";
//        $special_array[] = "(";
//        $special_array[] = ")";
        $special_array[] = "#";
//        $special_array[] = "]";
//        $special_array[] = "[";
        for ($i = 65; $i < 91; $i++) {
            $array_alpha[] = chr($i);
        }
        $random_special = array_rand($special_array, 2);
        $random_alpha = array_rand($array_alpha, 3);
        $random_special_val = $special_array[$random_special[0]];
        $random_alpha_val = $array_alpha[$random_alpha[0]];
        $random_alpha_vall = $array_alpha[$random_alpha[1]];
        $result = $random_alpha_val . $psw . $random_special_val . $random_alpha_vall;
        return $result;
    }

    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {

        return static::findOne(['email' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    public function getGroup() {
        return $this->hasOne(UserGroup::className(), ['id' => 'user_group']);
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {

        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

}
