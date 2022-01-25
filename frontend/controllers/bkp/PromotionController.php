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
class PromotionController extends Controller {

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
//                    'actions' => ['get', 'index'],
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

        $name = "Partners";
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
//                            if ($usermodel->user_type != 3) { // check it is not a guest
                            $action = strtolower($_SERVER['REQUEST_METHOD']); // Getting action from request header
                            $data = $this->$action($headers, $post); // Call respective action with post data and headers
                            if ($data != NULL) { // check the result have value
                                if ($data['error'] != NULL) { // Error Found on the reques
                                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'promotion');
                                    \Yii::$app->response->data = $array;
                                } else if ($data['data'] != NULL) { //success
                                    $transaction->commit();
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'promotion');
                                } else { // NO data Found based  on request
                                    $transaction->commit();
                                    // \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'promotion');
                                    $arrayy = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, [], 'promotion');
                                    $array['message'] = Yii::$app->ManageRequest->getMessage('no_promotion_available', $lang);
                                    $array['status'] = 200;
                                    $array['data']['value'] = [];
                                    \Yii::$app->response->data = $array;
                                    //  \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'promotion');
                                }
                            } else { // NO data Found based  on request
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, [], 'promotion');
                            }
//                            } else { //Un autherised Auth Token
//                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'promotion');
//                            }
                        } else { //Un autherised Auth Token
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'promotion');
                        }
                    } else { //Un autherised Auth Token
                        \Yii::$app->response->statusCode = 401;
                        $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'promotion');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Auth token not provided
                    \Yii::$app->response->statusCode = 401;
                    $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'promotion');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'promotion');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'promotion');
        }
    }

    public function get($headers, $post = []) { // Geting Data
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $finaldata = [];
        $return = [];
        $errors = [];
        $Id = Yii::$app->request->get('id'); // Getting review id from url
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url
        //Building query based on input get params
        // Getting Promotional data from Product and services
        $current_date = date('Y-m-d');
        $query = \common\models\Discounts::find()->where(['status' => 1]); // Check the Promotion Exist is approved by admin
        $query->andWhere("discount_from <= '" . $current_date . "' AND discount_to >= '" . $current_date . "'");
        if (isset($Id) && $Id != "") { //url carriying ID
            $productquery = \common\models\ProductsServices::find()->where(['status' => 1]); // Check the review is approved by admin
            $productquery->andWhere(['id' => $Id]);
            $models = $query->one();
            if ($models != NULL) {

                $return = [
                    "id" => $models->id,
                    "name" => $lang == 1 ? $models->product_name_en : $models->product_name_ar,
                    "description" => $lang == 1 ? $models->short_description_en : $models->short_description_ar,
                    "price" => $models->price,
                    "current_date" => date('Y-m-d'),
                    "discount_from" => $models->discounts->discount_from,
                    "discount_to" => $models->discounts->discount_to,
                    "discount_rate" => $models->discounts->discount_rate,
                    "display_price" => Yii::$app->Products->price($models),
                    "discount_type" => $models->discounts->discount_type, // 1-FLat Rate,2-Percentage
                    "discount_type_name" => $models->discounts->discount_type == 1 ? "Flat Rate" : "Percentage", // 1-FLat Rate,2-Percentage
                    "currency_id" => $models->merchant->franchise->currency,
                    "currency_name" => $models->merchant->franchise->currency0->name,
                    "currency_shortcode" => $models->merchant->franchise->currency0->shortcode,
                    "category" => $models->category,
                    "type" => $models->type,
                    "type_name" => $models->type == 1 ? "Product" : ($models->type == 2 ? "Shop Service" : ($models->type == 3 ? "Online Service" : "")),
                    'image' => $models->image != "" ? "uploads/products/" . base64_encode($models->sku) . "/image/medium/" . $models->image : "img/no-image.jpg",
                ];
            }
        } else { // not carrying
            $models_data = $query->all();

            if ($models_data != NULL) {
                foreach ($models_data as $models_dat) {
                    if ($models_dat->item_type == 1) {
                        $merchant = \common\models\Merchant::find()->where(['status' => 10, 'id' => $models_dat->merchant_id])->one(); // Check the review is approved by admin
                        if ($merchant != NULL) {
                            $result = [
                                "id" => $models_dat->id,
                                "item_id" => strval($merchant->id),
                                "name" => $lang == 1 ? $merchant->business_name : $merchant->business_name_ar,
                                "store_name" => "",
                                "description" => $lang == 1 ? $merchant->description : $merchant->description_ar,
                                "price" => null,
                                "current_day" => date('d'),
                                "current_month" => date('M'),
                                "current_year" => date('Y'),
                                "coupon_code" => $models_dat->coupon_code,
                                "discount_from" => $models_dat->discount_from,
                                "discount_to" => $models_dat->discount_to,
                                "discount_rate" => $models_dat->discount_rate,
                                "display_price" => "",
                                "discount_price" => "",
                                "discount_type" => $models_dat->discount_type, // 1-FLat Rate,2-Percentage
                                "discount_type_name" => $models_dat->discount_type == 1 ? "Flat Rate" : "Percentage", // 1-FLat Rate,2-Percentage
                                "currency_id" => $merchant->franchise->currency,
                                "currency_name" => $merchant->franchise->currency0->name,
                                "currency_shortcode" => $merchant->franchise->currency0->shortcode,
                                "item_type" => $models_dat->item_type,
                                "category" => null,
                                "type" => null,
                                "type_name" => "",
                                "review_rating" => $merchant->rating() <= 0 ? strval(5) : $merchant->rating(),
                                "image" => $merchant->business_logo != "" ? "uploads/merchant/" . $merchant->id . "/logo/" . $merchant->business_logo : "img/no-image.jpg",
                            ];
                            array_push($return, $result);
                        }
                    } else if ($models_dat->item_type == 2) {
                        $productquery = \common\models\ProductsServices::find()->where(['status' => 1]); // Check the review is approved by admin
                        $productquery->andWhere(['discount_id' => $models_dat->id]);
                        if (isset($limit) && $limit != "") {
                            $productquery->limit($limit);
                        }
//                        if (isset($offset) && $offset != "") {
//                            $offset = ($offset - 1) * $limit;
//                            $productquery->offset($offset);
//                        }
                        $models_products = $productquery->all();

                        if ($models_products != NULL) {
                            foreach ($models_products as $models) {
                                $result = [
                                    "id" => $models_dat->id,
                                    "item_id" => strval($models->id),
                                    "name" => $lang == 1 ? $models->product_name_en : $models->product_name_ar,
                                    "store_name" => $lang == 1 ? $models->merchant->business_name : $models->merchant->business_name,
                                    "description" => $lang == 1 ? $models->short_description_en : $models->short_description_ar,
                                    "price" => Yii::$app->Products->price($models),
                                    "current_day" => date('d'),
                                    "current_month" => date('M'),
                                    "current_year" => date('Y'),
                                    "coupon_code" => $models->discounts->coupon_code,
                                    "discount_from" => $models->discounts->discount_from,
                                    "discount_to" => $models->discounts->discount_to,
                                    "discount_type" => $models->discounts->discount_type, // 1-FLat Rate,2-Percentage
                                    "discount_rate" => $models->discounts->discount_rate,
                                    "display_price" => Yii::$app->Products->PriceConvert($models, $lang),
                                    "discount_price" => Yii::$app->Currency->Convert(floatval(Yii::$app->Products->DiscountPrice($models)), $models->merchant->franchise_id, $lang),
                                    "discount_type_name" => $models->discounts->discount_type == 1 ? "Flat Rate" : "Percentage", // 1-FLat Rate,2-Percentage
                                    "currency_id" => $models->merchant->franchise->currency,
                                    "currency_name" => $models->merchant->franchise->currency0->name,
                                    "currency_shortcode" => $models->merchant->franchise->currency0->shortcode,
                                    "category" => $models->category->id,
                                    "item_type" => $models->discounts->item_type,
                                    "type" => $models->type,
                                    "type_name" => $models->type == 1 ? "Product" : ($models->type == 2 ? "Shop Service" : ($models->type == 3 ? "Online Service" : "")),
                                    "review_rating" => $models->rating() <= 0 ? strval(5) : $models->rating(),
                                    'image' => $models->image != "" ? "uploads/products/" . base64_encode($models->sku) . "/image/medium/" . $models->image : "img/no-image.jpg",
                                ];
                                array_push($return, $result);
                            }
                        }
                    }
                }
            }
        }
        $start = ($offset - 1) * $limit;
        $newreturn = array_slice($return, $start, $limit, true);
        $finaldata['error'] = $errors;
        $finaldata['data'] = $newreturn;
        return $finaldata; // return error and data
    }

}
