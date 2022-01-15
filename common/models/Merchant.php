<?php

namespace common\models;

use yii\web\IdentityInterface;
use Yii;

/**
 * This is the model class for table "merchant".
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
 * @property float $rating
 * @property string $business_name
 * @property string $business_logo
 * @property string $business_gallery
 * @property string $alternative_email_address
 * @property string $return_policy
 * @property string $shipping_terms
 * @property int $franchise_id
 *
 * @property Country $country0
 * @property States $state0
 * @property Franchise $franchise
 * @property OrderProducts[] $orderProducts
 * @property ProductsServices[] $productsServices
 */
class Merchant extends \yii\db\ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $location;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'merchant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
//            [['first_name', 'last_name', 'email', 'password', 'state', 'created_by_type', 'updated_by', 'updated_by_type', 'shipping_type', 'business_name', 'alternative_email_address', 'return_policy', 'shipping_terms', 'franchise_id', 'user_id'], 'required'],
            [['status', 'country', 'mobile_number', 'franchise_id', 'location', 'latitude', 'longitude', 'category'], 'required', 'on' => 'update_merchant'],
            [['gender', 'country', 'state', 'status', 'newsletter', 'emailverify', 'created_by', 'created_by_type', 'updated_by', 'updated_by_type', 'shipping_type', 'payment_type', 'franchise_id'], 'integer'],
            [['first_name', 'last_name', 'cr_no', 'business_name', 'type_of_business', 'user_id'], 'required', 'on' => 'register_merchant'],
            [['dob', 'created_at', 'updated_at', 'state', 'city', 'area', 'location', 'role', 'category', 'facebook', 'whatsapp', 'instagram', 'availability_from', 'availability_to', 'availability', 'availability_interval', 'search_tag', 'description', 'description_ar'], 'safe'],
            [['address', 'business_gallery', 'return_policy', 'shipping_terms'], 'string'],
            [['rating'], 'number'],
            [['first_name', 'last_name', 'password_reset_token', 'profile_image'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 120],
            [['password', 'business_logo'], 'string', 'max' => 100],
            [['mobile_number'], 'string', 'max' => 15],
            [['auth_key', 'business_name', 'alternative_email_address'], 'string', 'max' => 255],
//            [['user_otp'], 'string', 'max' => 12],
            [['email'], 'unique'],
            [['country'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country' => 'id']],
            [['state'], 'exist', 'skipOnError' => true, 'targetClass' => States::className(), 'targetAttribute' => ['state' => 'id']],
            [['franchise_id'], 'exist', 'skipOnError' => true, 'targetClass' => Franchise::className(), 'targetAttribute' => ['franchise_id' => 'id']],
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
            'category' => 'Business Cateogry',
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
            'payment_type' => 'Payment Type',
            'rating' => 'Rating',
            'business_name' => 'Business Name',
            'business_logo' => 'Business Logo',
            'business_gallery' => 'Business Gallery',
            'alternative_email_address' => 'Alternative Email Address',
            'return_policy' => 'Return Policy',
            'shipping_terms' => 'Shipping Terms',
            'franchise_id' => 'Franchise ID',
            'location' => 'Store/Service Location',
        ];
    }

    public function uploadFile($file, $name, $folder) {
        $imagine = new \Imagine\Imagick\Imagine();
        $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $sizes = \Yii::$app->params['merchant_image_size'];
        $targetFolder = \yii::$app->basePath . '/../uploads/' . $folder . '/';
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }
        if ($file->saveAs($targetFolder . $name . '.' . $file->extension)) {
            if ($sizes != NULL) {
                foreach ($sizes as $foldername => $value) {
                    $sizeexp = explode('*', $value);
                    if ($sizeexp != NULL) {
                        $size = new \Imagine\Image\Box($sizeexp[0], $sizeexp[1]);
                        $result_folder = $targetFolder . '/' . $foldername . '/';
                        if (!file_exists($result_folder)) {
                            mkdir($result_folder, 0777, true);
                        }

                        $imagine->open($targetFolder . $name . '.' . $file->extension)
                                ->thumbnail($size, $mode)
                                ->save($result_folder . $name . '.' . $file->extension);
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function uploadMultipleImage($uploadfile, $id, $name, $foldername = false) {
        $imagine = new \Imagine\Imagick\Imagine();
        $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $sizes = \Yii::$app->params['merchant_image_gallery'];
        $targetFolder = \yii::$app->basePath . '/../uploads/' . $foldername . '/';

        $model = Merchant::find()->where(['status' => 10, 'id' => $id])->one();

        if (!is_dir(Yii::$app->basePath . '/../uploads/' . $foldername)) {
            mkdir(Yii::$app->basePath . '/../uploads/' . $foldername);
            chmod(Yii::$app->basePath . '/../uploads/' . $foldername . '', 0777);
        }
        $i = 1;
        if ($model->business_gallery != '') {
            $name_array = explode(',', $model->business_gallery);
        } else {
            $name_array = [];
        }

        foreach ($uploadfile as $upload) {
            if (isset($upload)) {
                $gallery_name = 'gallery' . $name . $i;
                if ($upload->saveAs($targetFolder . $gallery_name . '.' . $upload->extension)) {
                    chmod($targetFolder . $gallery_name . '.' . $upload->extension, 0777);
                    $name_array[] = $gallery_name . '.' . $upload->extension;
                    if ($sizes != NULL) {
                        foreach ($sizes as $foldername => $value) {
                            $sizeexp = explode('*', $value);
                            if ($sizeexp != NULL) {
                                $size = new \Imagine\Image\Box($sizeexp[0], $sizeexp[1]);
                                $result_folder = $targetFolder . '/' . $foldername . '/';
                                if (!is_dir($result_folder)) {
                                    mkdir($result_folder, 0777, true);
                                }
                                $imagine->open($targetFolder . $gallery_name . '.' . $upload->extension)
                                        ->thumbnail($size, $mode)
                                        ->save($result_folder . $gallery_name . '.' . $upload->extension);
                            }
                        }
                    }
                }
            }
            $i++;
        }

        if ($model != NULL) {

            if ($name_array != NULL) {


                $model->business_gallery = implode(',', $name_array);
                $model->save(FALSE);
            }
        }
        return true;
    }

    /**
     * Gets query for [[Country0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry0() {
        return $this->hasOne(Country::className(), ['id' => 'country']);
    }

    public function getUser() {
        return $this->hasOne(common\models\User::className(), ['id' => 'user_id']);
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
     * Gets query for [[Franchise]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFranchise() {
        return $this->hasOne(Franchise::className(), ['id' => 'franchise_id']);
    }

    public function getBusinessType() {
        return $this->hasOne(BusinessCategory::className(), ['id' => 'type_of_business']);
    }

    /**
     * Gets query for [[OrderProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts() {
        return $this->hasMany(OrderProducts::className(), ['merchant_id' => 'id']);
    }

    public function getSubscriptions() {
        return $this->hasMany(Subscription::className(), ['merchant_id' => 'id']);
    }

    /**
     * Gets query for [[ProductsServices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductsServices() {
        return $this->hasMany(ProductsServices::className(), ['merchant_id' => 'id']);
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

    public function getRoles() {
        return $this->hasOne(AdminRole::className(), ['id' => 'role']);
    }

    public function getProductReviews() {
        return $this->hasMany(ProductReview::className(), ['review_for_id' => 'id'])->andOnCondition('approvel = 1 AND review_type = 3');
    }

    public function Reviews() {
        $ratings = [];
        $model = ProductReview::find()->where(['review_for_id' => $this->id])->andWhere("approvel = 1 AND review_type = 3")->all();
        if ($model != NULL) {
            foreach ($model as $models) {
                array_push($ratings, [
                    "id" => $models->id,
                    "user_id" => $models->user_id,
                    "username" => $models->user->first_name . ' ' . $models->user->last_name,
                    "designation" => $models->designation,
                    "review_type" => $models->review_type,
                    "review_for_id" => $models->review_for_id,
                    "rating" => $models->rating,
                    "comment" => $models->comment,
                    'customer_image' => $models->user->profile_image != "" ? "uploads/user/" . $models->user_id . "/" . $models->user->profile_image : "img/no-image.jpg",
                ]);
            }
        }
        return $ratings;
    }

    public function getProduct() {
        return $this->hasMany(ProductsServices::className(), ['merchant_id' => 'id']);
    }

    public function getCity0() {
        return $this->hasOne(City::className(), ['id' => 'city']);
    }

    public function Rating() {
        $get_ratings = ProductReview::find()->where(['review_for_id' => $this->id])->andWhere("review_type = 3")->all();
        $total = 0;
        $result = 0;
        if ($get_ratings != NULL) {
            $count = count($get_ratings);
            foreach ($get_ratings as $get_rating) {
                $total += $get_rating->rating;
            }
            if ($count > 0) {
                $result = $total / $count;
            }
        }
        return strval($result);
    }

    public function Products($lang) {
        $get_products = ProductsServices::find()->where(['merchant_id' => $this->id])->all();
        $products = [];
        if ($get_products != NULL) {
            foreach ($get_products as $get_product) {
                array_push($products, [
                    'id' => $get_product->id,
                    'name' => $lang == 1 ? $get_product->product_name_en : $get_product->product_name_ar,
                    'category_id' => $get_product->category_id,
                    "category_name" => $lang == 1 ? $get_product->category->category_name : $get_product->category->category_name_ar,
                    "image" => $get_product->image != "" ? "uploads/products/" . base64_encode($get_product->sku) . "/image/related/" . $get_product->image : "img/no-image.jpg",
                    "display_price" => Yii::$app->Products->priceConvert($get_product),
                    "price" => Yii::$app->Products->price($get_product),
                ]);
            }
        }
        return $products;
    }

    public function ProductCategory($lang) {
        $get_products = ProductsServices::find()->select('category_id')->where(['merchant_id' => $this->id])->asArray()->all();
        $category = [];
        if ($get_products != NULL) {
            $catsexp = array_column($get_products, 'category_id');
            if ($catsexp != NULL) {
                $catsimp = implode(',', $catsexp);
                if ($catsimp != '') {
                    $cats = explode(',', $catsimp);
                }
            }
            if ($cats != NULL) {
                $catlist = \common\models\Category::find()->where(['IN', 'id', $cats])->all();
                if ($catlist != NULL) {
                    foreach ($catlist as $catli) {
                        array_push($category, [
                            'id' => $catli->id,
                            'category_name' => $lang == 1 ? $catli->category_name : $catli->category_name_ar
                        ]);
                    }
                }
            }
        }
        return $category;
    }

    public function getMyFavourite($uid) {

        return Favorites::find()->select('id,user_id,favourite_for_id,favourite_type,status')->where(['and', ['favourite_for_id' => $this->id, 'user_id' => $uid]])->andWhere(['status' => 1])->andWhere('favourite_type =3 AND status=1')->all();
    }

}
