<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\LoginForm;
use yii\web\Response;
use yii\helpers\Json;
use yii\filters\Cors;
use yii\web\UploadedFile;
use frontend\controllers\CrmController;

/**
 * Site controller
 */
class ItemController extends Controller {

    public $enableCsrfValidation = false;

    public static function allowedDomains() {
        date_default_timezone_set('Asia/Qatar');
        return [
            '*', // star allows all domains
// 'http://test1.example.com',
// 'http://test2.example.com',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        date_default_timezone_set('Asia/Qatar');
        $behaviors = parent::behaviors();


        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
// restrict access to
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                // Allow only POST and PUT methods
                'Access-Control-Request-Headers' => ['*'],
                // Allow only headers 'X-Wsse'
                'Access-Control-Allow-Credentials' => true,
                // Allow OPTIONS caching
                'Access-Control-Max-Age' => 30,
            // Allow the X-Pagination-Current-Page header to be exposed to the browser.
// 'Access-Control-Expose-Headers' => [],
            ]
        ];
//        $behaviors['access'] = [
//            'class' => \yii\filters\AccessControl::className(),
//            'rules' => [
//                [
//                    'actions' => ['post', 'index'],
//                    'allow' => true,
//                    'roles' => ['?'],
//                ],
//            ],
//        ];

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::className(),
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];
        return $behaviors;
    }

    public function init() {
//        date_default_timezone_set('Asia/Qatar');
        parent::init();
    }

    public function actionIndex() {

        $name = "Products";
        header('Content-type:appalication/json'); //Header content set to json
        $headers = Yii::$app->request->headers;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Set output format structure
        if (isset($headers['lang']) && $headers['lang'] != "") { //setting App language
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $data = [];
        $json = file_get_contents('php://input'); // Getting Post Data
        $post = json_decode($json, true); // Converting into Array
        if (isset($headers['accessToken']) && $headers['accessToken'] != "") { // Chech the Access token is set or not
            if (Yii::$app->ManageRequest->validateAccessToken($headers['accessToken'])) { // Validate Acccess Token
                if (isset($headers['authToken']) && $headers['authToken'] != "") { //Check the AuthToken Is Set
                    if (Yii::$app->ManageRequest->validateAuthToken($headers['authToken']) != NULL) { // Check The authToken is Valids
                        $transaction = Yii::$app->db->beginTransaction();
                        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
                        $usermodel = \common\models\User::findOne(['id' => $userId]);
                        if ($usermodel != NULL) { //check usrr exist
                            //  if ($usermodel->user_type != 3) { // check it is not a guest
                            $action = strtolower($_SERVER['REQUEST_METHOD']); // Getting action from request header
                            $data = $this->$action($headers, $post); // Call respective action with post data and headers
                            if ($data != NULL) { // check the result have value
                                if ($data['error'] != NULL) { // Error Found on the reques
                                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data['error'], 'products');
                                    \Yii::$app->response->data = $array;
                                } else if ($data['data'] != NULL) { //success
                                    $transaction->commit();
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'products');
                                } else { // NO data Found based  on request
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data, 'products');
                                }
                            } else { // NO data Found based  on request
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data, 'products');
                            }
//                            } else { //Un autherised Auth Token
//                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'products');
//                            }
                        } else { //Un autherised Auth Token
                            \Yii::$app->response->statusCode = 401;

                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'products');
                        }
                    } else { //Un autherised Auth Token
                        \Yii::$app->response->statusCode = 401;

                        $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'products');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Auth token not provided
                    \Yii::$app->response->statusCode = 401;

                    $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'products');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'products');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'products');
        }
    }

    public function post($headers, $post = []) { // post operation for creating reviews
        $name = "Product_service_store Detail Page";
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $finaldata = [];
        $return = [];
        $errors = [];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        $params = ["item_type", "id"];
        if (isset($post) && $post != NULL) { // checking post data exist
            if ($params != NULL) {
                foreach ($params as $param) {
                    if (isset($post[$param])) {
                        if ($post[$param] == NULL || $post[$param] == "") {
                            $errors[$param] = $post[$param];
                        }
                    } else {
                        $errors[$param] = "";
                    }
                }
            }
            if ($errors == NULL) { // Any Error in the post data
                if ($post['item_type'] == 1 || $post['item_type'] == 2) { //1-store/merchant,2-product/service
                    if ($post['item_type'] == 1) { //1-store/merchant,2-product/service
                        $query = \common\models\Merchant::find()
                                ->select("merchant.id as merchant_id,merchant.*")
                                ->where(['id' => $post['id'], 'status' => 10]);
                    } else if ($post['item_type'] == 2) {

                        $query = \common\models\ProductsServices::find()
                                ->select("products_services.*,products_services.id as pid,merchant.*")
                                ->where(['products_services.id' => $post['id'], 'products_services.status' => 1])
                                ->innerJoinWith('merchant', false);
                    }
                    $models_data = $query->asArray()->all();

                    if ($models_data != NULL) {
                        foreach ($models_data as $models) {

                            $merchant = \common\models\Merchant::findOne($models['merchant_id']);
                            $merchant_feature_list = \common\models\MerchantFeatureList::find()->where(['merchant_id' => $models['merchant_id'], 'status' => 1])->all();
                            $fet_list = [];
                            if ($merchant_feature_list != NULL) {
                                foreach ($merchant_feature_list as $mode) {
                                    array_push($fet_list, ["feature_id" => $mode->feature_id, "key" => $lang == 1 ? $mode->featureList->name_en : $mode->featureList->name_ar, "value" => $lang == 1 ? $mode->value_en : ($lang == 2 ? $mode->value_ar : "")]);
                                }
                            }
                            $gallery = [];
                            $payment_options = [];
                            $payment_options_query = \common\models\PaymentOptions::find()->where(['status' => 1])->all();
                            if ($payment_options_query != NULL) {
                                foreach ($payment_options_query as $payment_options_que) {
                                    array_push($payment_options, [
                                        'id' => $payment_options_que->id,
                                        'name' => $lang == 1 ? $payment_options_que->name : $payment_options_que->name_ar,
                                        'image' => $payment_options_que->image != "" ? '/uploads/payment/' . $payment_options_que->image : "img/no-image.jpg",
                                    ]);
                                }
                            }
                            if ($post['item_type'] == 1) { //1-store/merchant,2-product/service
                                $purchase_status = \common\models\OrderProducts::find()->where(['user_id' => $userId, 'merchant_id' => $post['id']])->andWhere('status >= 2')->all();
                                $merchantcategory = [];
                                $category = [];
                                if ($merchant->category != "") {
                                    $exp_category = explode(",", $merchant->category);
                                    if ($exp_category != NULL) {
                                        foreach ($exp_category as $exp_cat) {
                                            $merchantcat = \common\models\MerchantCategory::findOne(['id' => $exp_cat]);
                                            if ($merchantcat != NULL) {
                                                array_push($merchantcategory, [
                                                    'id' => $merchantcat->id,
                                                    'category_name' => $lang == 1 ? $merchantcat->name : $merchantcat->name_ar
                                                ]);
                                            }
                                        }
                                    }
                                }
                                if ($merchant->business_gallery != "") {
                                    $exp_gallery = explode(",", $merchant->business_gallery);
                                    if ($exp_gallery != NULL) {
                                        foreach ($exp_gallery as $gall) {
                                            $small = "";
                                            $large = "";
                                            $imgPath = \Yii::$app->basePath . '/uploads/merchant/' . $merchant->id . '/gallery/' . $gall;
                                            if (!file_exists($imgPath)) {
                                                $small = '/uploads/merchant/' . $merchant->id . '/gallery/small/' . $gall;
                                                $large = '/uploads/merchant/' . $merchant->id . '/gallery/large/' . $gall;
                                            }
                                            array_push($gallery, ["small" => $small, "large" => $large]);
                                        }
                                    }
                                }
                                $catlist = \common\models\Category::findAll(['status' => 1]);
                                if ($catlist != NULL) {
                                    foreach ($catlist as $catli) {
                                        array_push($category, [
                                            'id' => $catli->id,
                                            'category_name' => $lang == 1 ? $catli->category_name : $catli->category_name_ar
                                        ]);
                                    }
                                }
                                $return = [
                                    "id" => $models['merchant_id'],
                                    "name" => $merchant ? ($lang == 1 ? $merchant->business_name : $merchant->business_name_ar) : "",
                                    "description" => $lang == 1 ? $merchant->description : $merchant->description_ar,
                                    "features" => $fet_list,
                                    "availability_status" => $merchant->availability,
                                    "review_status" => $merchant->productReviews != NULL ? "1" : "0",
                                    "review_rating" => $merchant->rating() <= 0 ? strval(5) : $merchant->rating(),
                                    "reviews" => $merchant->Reviews(),
                                    "favourite_status" => $merchant->getMyFavourite($userId) != NULL ? "1" : "0",
                                    "products" => $merchant->products($lang),
                                    "store_category" => $merchantcategory,
                                    "category" => $merchant->ProductCategory($lang),
                                    "phone_number" => $merchant->mobile_number,
                                    "facebook" => $merchant->facebook,
                                    "instagram" => $merchant->instagram,
                                    "whatsapp" => $merchant->whatsapp,
                                    "latitude" => $merchant->latitude,
                                    "longitude" => $merchant->longitude,
                                    "purchase_status" => $purchase_status != NULL ? "1" : "0",
                                    "payment_options" => $payment_options,
                                    "gallery" => $gallery,
                                ];
                            } else if ($post['item_type'] == 2) {
                                $purchase_status = \common\models\OrderProducts::find()->where(['user_id' => $userId, 'product_id' => $post['id']])->andWhere('status >= 2')->all();
                                $attributes = $this->getProductAttributes($models['pid']);
                                $mainattributes = $this->getProductAttributesMain($models['pid']);
                                $pmodel = \common\models\ProductsServices::findOne($models["pid"]);
                                if ($pmodel != NULL) {
                                    if ($pmodel->gallery != "") {
                                        $exp_gallery = explode(",", $pmodel->gallery);
                                        if ($exp_gallery != NULL) {
                                            foreach ($exp_gallery as $gall) {
                                                $small = "";
                                                $large = "";
                                                $imgPath = \Yii::$app->basePath . '/uploads/products/' . base64_encode($pmodel->sku) . '/gallery/' . $gall;
                                                if (!file_exists($imgPath)) {
                                                    $small = '/uploads/products/' . base64_encode($pmodel->sku) . '/gallery/small/' . $gall;
                                                    $large = '/uploads/products/' . base64_encode($pmodel->sku) . '/gallery/large/' . $gall;
                                                }
                                                array_push($gallery, ["small" => $small, "large" => $large]);
                                            }
                                        }
                                    }
                                    $days_availability = \common\models\WeekDaysAvailability::find()->where(['merchant_id' => $models['merchant_id'], 'availability' => 1])->all();
                                    $return = [
                                        "id" => $models['pid'],
                                        "name" => $lang == 1 ? $models['product_name_en'] : $models['product_name_ar'],
                                        "display_price" => Yii::$app->Products->priceConvert($pmodel, $lang),
                                        "price" => Yii::$app->Products->price($pmodel),
                                        "long_description" => $lang == 1 ? $models['long_description_en'] : $models['long_description_ar'],
                                        "description" => $lang == 1 ? $models['short_description_en'] : $models['short_description_ar'],
                                        "category_id" => $pmodel->category_id,
                                        "category_name" => $lang == 1 ? $pmodel->category->category_name : $pmodel->category->category_name_ar,
                                        "merchant_id" => $models['merchant_id'],
                                        "merchant_name" => $merchant ? ($lang == 1 ? $merchant->business_name : $merchant->business_name_ar) : "",
                                        //  "features" => $fet_list,
                                        "merchant_average_rating" => $merchant->rating() <= 0 ? strval(5) : $merchant->rating(),
                                        "availability_status" => $merchant->availability != null ? strval($merchant->availability) : "0",
                                        //  "availability_status" => $models['type'] == 1 ? ($merchant->availability != null ? strval($merchant->availability) : "0") : ( $days_availability != NULL ? "1" : "0"),
                                        "item_type" => $models['type'], //1-Product,2-Home Service,3-Shop Service
                                        "item_type_name" => $models['type'] == 1 ? "Product" : ($models['type'] == 2 ? "Shop Service" : ($models['type'] == 3 ? "Online Service" : "")),
                                        "discount_type" => $models['discount_type'], // 1-FLat Rate,2-Percentage
                                        "discount_type_name" => $models['discount_type'] == 1 ? "Flat Rate" : "Percentage", // 1-FLat Rate,2-Percentage
                                        "review_status" => $pmodel->productReviews != NULL ? "1" : "0",
                                        "review_rating" => $pmodel->rating() <= 0 ? strval(5) : $pmodel->rating(),
                                        "reviews" => $pmodel->Reviews(),
                                        "favourite_status" => $pmodel->getMyFavourite($userId) != NULL ? "1" : "0",
                                        //        "favourite" => $pmodel->getMyFavourite($userId),
                                        "currency_id" => $pmodel->merchant->franchise->currency,
                                        "currency_name" => $pmodel->merchant->franchise->currency0->name,
                                        "currency_shortcode" => $lang == 1 ? $pmodel->merchant->franchise->currency0->shortcode : $pmodel->merchant->franchise->currency0->shortcode_ar,
                                        "purchase_status" => $purchase_status != NULL ? "1" : "0",
                                        "attributes" => $attributes,
//                                        "date_status" => $days_availability != NULL ? TRUE : FALSE,
                                        "date_status" => $models['type'] == 1 ? FALSE : TRUE,
                                        "mainattributes" => $mainattributes != NULL ? $mainattributes : null,
                                        "gallery" => $gallery,
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

    private function getProductAttributes($pid) {
        $attributes = [];
        $get_attributes = \common\models\ProductAttributesValue::find()
                        ->select("product_attributes.*,product_attributes_value.id,attributes_value_id,price,attributes_value.value as attributes_value,attributes.name as name,attributes.id as attributes_id")
                        ->where(['product_attributes_value.status' => 1, 'product_attributes_value.product_id' => $pid])
                        ->andWhere('product_attributes.price_status != 1')
                        ->innerJoinWith('attributesValue', false)
                        ->join('LEFT OUTER JOIN', 'attributes', 'attributes_value.attributes_id =attributes.id')
                        ->join('LEFT OUTER JOIN', 'product_attributes', 'product_attributes_value.product_attributes_id =product_attributes.id')
                        ->orderBy(['product_attributes_value.sort_order' => SORT_ASC])
                        ->asArray()->all();
        $attributes_lists = array_unique(array_column($get_attributes, 'attributes_id'));
        if ($attributes_lists != NULL) {
            foreach ($attributes_lists as $attributes_list) {
                $product_attr_items = [];
                foreach ($get_attributes as $get_attribute) {
                    if ($attributes_list == $get_attribute['attributes_id']) {
                        $name = $get_attribute['name'];

                        if (isset($get_attribute['name'])) {
                            unset($get_attribute['name']);
                        }
                        if (isset($get_attribute['attributes_id'])) {
                            unset($get_attribute['attributes_id']);
                        }
                        if (isset($get_attribute['attributes_value_id'])) {
                            unset($get_attribute['attributes_value_id']);
                        }
//                        if (isset($get_attribute['id'])) {
//                            $get_attribute['id'] = (int) $get_attribute['id'];
//                        }
                        array_push($product_attr_items, $get_attribute);
                    }
                }
                array_push($attributes, ['attribute_id' => (int) $attributes_list, 'attribute_name' => $name, 'attr_items' => $product_attr_items]);
            }
        }
        return $attributes;
    }

    private function getProductAttributesMain($pid) {
        $attributes = [];
        $get_attributes = \common\models\ProductAttributesValue::find()
                        ->select("product_attributes.*,product_attributes_value.id,attributes_value_id,price,attributes_value.value as attributes_value,attributes.name as name,attributes.id as attributes_id")
                        ->where(['product_attributes_value.status' => 1, 'product_attributes_value.product_id' => $pid])
                        ->andWhere(['product_attributes.price_status' => 1])
                        ->innerJoinWith('attributesValue', false)
                        ->join('LEFT OUTER JOIN', 'attributes', 'attributes_value.attributes_id =attributes.id')
                        ->join('LEFT OUTER JOIN', 'product_attributes', 'product_attributes_value.product_attributes_id =product_attributes.id')
                        ->orderBy(['product_attributes_value.sort_order' => SORT_ASC])
                        ->asArray()->all();
        $attributes_lists = array_unique(array_column($get_attributes, 'attributes_id'));
        if ($attributes_lists != NULL) {
            foreach ($attributes_lists as $attributes_list) {
                $product_attr_items = [];
                foreach ($get_attributes as $get_attribute) {
                    if ($attributes_list == $get_attribute['attributes_id']) {
                        $name = $get_attribute['name'];

                        if (isset($get_attribute['name'])) {
                            unset($get_attribute['name']);
                        }
                        if (isset($get_attribute['attributes_id'])) {
                            unset($get_attribute['attributes_id']);
                        }
                        if (isset($get_attribute['attributes_value_id'])) {
                            unset($get_attribute['attributes_value_id']);
                        }
                        array_push($product_attr_items, $get_attribute);
                    }
                }
                $attributes = ['attribute_id' => (int) $attributes_list, 'attribute_name' => $name, 'attr_items' => $product_attr_items];
                //array_push($attributes, ['attribute_id' => $attributes_list, 'attribute_name' => $name, 'attr_items' => $product_attr_items]);
            }
        }
        return $attributes;
    }

}
