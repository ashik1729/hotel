<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "products_services".
 *
 * @property int $id
 * @property int $category_id
 * @property string $product_name
 * @property string $canonical_name
 * @property string $image
 * @property string $gallery
 * @property int $sort_order
 * @property float $price
 
 * @property int $package_title
 * @property int $is_featured
 * @property string $created_at
 * @property string $updated_at
 * @property int $updated_by
 * @property int $created_by
 * @property int $status
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property int $quantity
 * @property string $short_description
 * @property string $long_description
 *
 * @property Cart[] $carts
 * @property PackagesDate[] $PackageDate
 * @property OrderHistory[] $orderHistories
 * @property OrderProducts[] $orderProducts
 * @property ProductAttributesValue[] $productAttributesValues
 * @property ProductReview[] $productReviews
 * @property ProductViewed[] $productVieweds
 * @property Category $category
 * @property Merchant $merchant
 * @property UserWishlist[] $userWishlists
 */
class ProductsServices extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'packages';
    }

    public $store;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            // [['category_id', 'merchant_id', 'price', 'requires_shipping', 'updated_by', 'created_by', 'quantity', 'type', 'package_title ', 'product_name_ar'], 'required'],
            [['category_id', 'sort_order', 'discount_type', 'is_featured',  'updated_by', 'created_by', 'status', 'quantity'], 'integer'],
            [['gallery', 'search_tag', 'meta_description', 'meta_keywords', 'short_description_en', 'long_description_en'], 'string'],
            [['price', 'discount_rate'], 'number'],
            [['gallery','short_description_en', 'long_description_en'], 'string'],
            [['id', 'created_at', 'updated_at', 'created_at', 'field', 'canonical_name', 'updated_at', 'short_description_en', 'short_description_ar', 'long_description_en','meta_title', 'meta_description', 'meta_keywords',
                'sort_order', 'image', 'gallery', 'package_title','overview','packaage_organize','destination'], 'safe'],
            
            [['package_title', 'canonical_name', 'meta_title'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 100],
            // [['sku'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            // [['merchant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Merchant::className(), 'targetAttribute' => ['merchant_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'Package ID',
            'category_id' => 'Category ID',
           // 'merchant_id' => 'Merchant ID',
            'package_title ' => 'Package Name ',
            'canonical_name' => 'Canonical Name',
            'image' => 'Image',
            'gallery' => 'Gallery',
            'sort_order' => 'Sort Order',
            'price' => 'Price',
            'overview'    => 'Overview',
            'packaage_organize' => 'Package Organize',
            'search_tag' => 'Search Tag',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_by' => 'Created By',
            'status' => 'Status',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'tax_applicable' => 'Tax Applicable',
            'quantity' => 'Quantity',
            'weight_class' => 'Weight Class',
            'short_description_en' => 'Description',
            'long_description_en' => 'Long Description',
        ];
    }

    public function uploadFile($file, $name, $folder) {


//$imagine = new Imagine\Gd\Imagine();
//// or
//$imagine = new Imagine\Imagick\Imagine();
//// or
//$imagine = new Imagine\Gmagick\Imagine();
//
//$size    = new Imagine\Image\Box(40, 40);
//
//$mode    = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
//// or
//$mode    = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
//
//$imagine->open('/path/to/large_image.jpg')
//    ->thumbnail($size, $mode)
//    ->save('/path/to/thumbnail.png')
//;

        $imagine = new \Imagine\Imagick\Imagine();
        $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $sizes = \Yii::$app->params['image_size'];
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
        $sizes = \Yii::$app->params['image_size'];
        $targetFolder = \yii::$app->basePath . '/../uploads/' . $foldername . '/';

        $model = ProductsServices::find()->where(['status' => 1, 'id' => $id])->one();
        if (!is_dir(Yii::$app->basePath . '/../uploads/' . $foldername)) {
            mkdir(Yii::$app->basePath . '/../uploads/' . $foldername);
            chmod(Yii::$app->basePath . '/../uploads/' . $foldername . '', 0777);
        }
        $i = 1;
        if ($model->gallery != '') {
            $name_array = explode(',', $model->gallery);
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


                $model->gallery = implode(',', $name_array);
                $model->save(FALSE);
            }
        }
        return true;
    }

    /**
     * Gets query for [[Carts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPackageDate() {
        return $this->hasMany(PackagesDate::className(), ['package_id' => 'id']);
    }

    public function getCarts() {
        return $this->hasMany(Cart::className(), ['product_id' => 'id']);
    }

    public function getProductLanguage() {
        return $this->hasOne(LanguageData::className(), ['item_id' => 'package_title ']);
    }

    public function getLongDesc() {
        return $this->hasOne(LanguageData::className(), ['item_id' => 'long_description_en']);
    }

    public function getShortDesc() {
        return $this->hasOne(LanguageData::className(), ['item_id' => 'short_description_en']);
    }

    /**
     * Gets query for [[OrderHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderHistories() {
        return $this->hasMany(OrderHistory::className(), ['product_id' => 'id']);
    }

    /**
     * Gets query for [[OrderProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts() {
        return $this->hasMany(OrderProducts::className(), ['product_id' => 'id']);
    }

    /**
     * Gets query for [[ProductAttributesValues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributesValues() {
        return $this->hasMany(ProductAttributesValue::className(), ['product_id' => 'id']);
    }

    /**
     * Gets query for [[ProductReviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function Rating() {
        $get_ratings = ProductReview::find()->where(['review_for_id' => $this->id])->andWhere("review_type = 1 OR review_type = 2")->all();
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

    public function getProductReviews() {
        return $this->hasMany(ProductReview::className(), ['review_for_id' => 'id'])->andOnCondition('approvel = 1 AND (review_type = 1 OR review_type = 2)');
    }

    public function Reviews() {
        $ratings = [];

        $model = ProductReview::find()->where(['review_for_id' => $this->id])->andWhere('approvel = 1 AND (review_type = 1 OR review_type = 2)')->all();
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

    /**
     * Gets query for [[ProductVieweds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductVieweds() {
        return $this->hasMany(ProductViewed::className(), ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Merchant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant() {
        return $this->hasOne(Merchant::className(), ['id' => 'merchant_id']);
    }

    /**
     * Gets query for [[Discounts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiscounts() {
        return $this->hasOne(Discounts::className(), ['id' => 'discount_id']);
    }
    public function getDestinations() {
        return $this->hasOne(PackageDestination::className(), ['id' => 'destination']);
    }

    /**
     * Gets query for [[UserWishlists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMyFavourite($uid) {

        return Favorites::find()->select('id,user_id,favourite_for_id,favourite_type,status')->where(['and', ['favourite_for_id' => $this->id, 'user_id' => $uid]])->andWhere(['status' => 1])->andWhere('(favourite_type =1 OR favourite_type=2) AND status=1')->all();
    }


    public function uploadBanner($file, $name, $folder) {

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
}
