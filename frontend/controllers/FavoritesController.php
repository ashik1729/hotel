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
class FavoritesController extends Controller {

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
//                    'actions' => ['get', 'post', 'index'],
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
                            if ($usermodel->user_type != 3) { // check it is not a guest
                                $action = strtolower($_SERVER['REQUEST_METHOD']); // Getting action from request header
                                $data = $this->$action($headers, $post); // Call respective action with post data and headers
                                if ($data != NULL) { // check the result have value
                                    if ($data['error'] != NULL) { // Error Found on the reques
                                        $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data['error'], 'favotites');
                                        \Yii::$app->response->data = $array;
                                    } else if ($data['data'] != NULL) { //success
                                        $transaction->commit();
                                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'favotites');
                                    } else { // NO data Found based  on request
                                        $arrayy = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, [], 'favotites');
                                        $array['message'] = Yii::$app->ManageRequest->getMessage('no_favorites_available', $lang);
                                        $array['status'] = 200;
                                        $array['data']['value'] = [];
                                        \Yii::$app->response->data = $array;
                                        //  \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'favotites');
                                    }
                                } else { // NO data Found based  on request
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'favotites');
                                }
                            } else { //Un autherised Auth Token
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'favotites');
                            }
                        } else { //Un autherised Auth Token
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'favotites');
                        }
                    } else { //Un autherised Auth Token
                        \Yii::$app->response->statusCode = 401;

                        $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'favotites');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Auth token not provided
                    \Yii::$app->response->statusCode = 401;

                    $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'favotites');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'favotites');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'favotites');
        }
    }

    public function post($headers, $post = []) { // post operation for creating favotites
        $name = "Search";
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $finaldata = [];
        $return = [];
        $errors = [];
        $params = ['favourite_for_id', 'favourite_type'];
        //favourite_for_id - Product ID
        //Status - Status may (1- add to favorite,0-Remove From Favorite)
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        if ($userId != "") {
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
                    $check_products = NULL;
                    if ($post['favourite_type'] == 1 || $post['favourite_type'] == 2) {
                        $check_products = \common\models\ProductsServices::find()->where(['id' => $post['favourite_for_id']])->one();
                    } else if ($post['favourite_type'] == 3) {
                        $check_products = \common\models\Merchant::find()->where(['id' => $post['favourite_for_id']])->one();
                    }
                    if ($check_products == NULL) {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(450, $name, $lang, $post, (object) [], 'favotites');
                        Yii::$app->end();
                    }
                    if (!isset($post['favourite_for_id']) || $post['favourite_for_id'] == "0" || $post['favourite_for_id'] == "") {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'favotites');
                        Yii::$app->end();
                    }

                    if (isset($post['status'])) {
                        if (($post['status'] != 1 && $post['status'] != 0)) {
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'favotites');
                            Yii::$app->end();
                        }
                    }
                    if ($post['favourite_type'] == 1 || $post['favourite_type'] == 2) {
                        $check_favorite = \common\models\Favorites::find()->where(['user_id' => $userId, 'favourite_for_id' => $post['favourite_for_id']])->andWhere('(favourite_type =1 OR favourite_type=2)')->one();
                    } else {
                        $check_favorite = \common\models\Favorites::find()->where(['user_id' => $userId, 'favourite_for_id' => $post['favourite_for_id']])->andWhere('favourite_type = 3')->one();
                    }
                    $query = new \common\models\Favorites();
                    if ($check_favorite != NULL) {
                        $query = $check_favorite;
                    } else {
                        $query->user_id = $userId;
                        $query->favourite_for_id = $post['favourite_for_id'];
                        $query->favourite_type = $post['favourite_type'];
                    }
                    if (isset($post['status'])) {

                        $query->status = $post['status'];
                    } else {
                        $query->status = 1;
                    }
                    if ($query->save()) {

                        if ($query->favourite_type == 1 || $query->favourite_type == 2) {
                            $pmodel = \common\models\ProductsServices::findOne(['id' => $query->favourite_for_id]);
                            if ($pmodel != NULL) {
                                $return = [
                                    "id" => $query->id,
                                    "user_id" => $query->user_id,
                                    "name" => $lang == 1 ? $pmodel->product_name_en : $pmodel->product_name_ar,
                                    "rating" => $pmodel->rating() <= 0 ? floatval(5) : floatval($pmodel->rating()),
                                    "reviews_count" => count($pmodel->Reviews()),
                                    "description" => $lang == 1 ? $pmodel->short_description_en : $pmodel->short_description_ar,
                                    "price" => Yii::$app->Products->priceConvert($pmodel, $lang),
                                    "favourite_for_id" => $query->favourite_for_id,
                                    "favourite_type" => $query->favourite_type, //1-Products,2-Services,3-Store
                                    "status" => $query->status,
                                    "image" => $pmodel->image != "" ? "uploads/products/" . base64_encode($pmodel->sku) . "/image/large/" . $pmodel->image : "img/no-image.jpg",
                                ];
                            }
                        } else if ($query->favourite_type == 3) {
                            $merchant = \common\models\Merchant::findOne(['id' => $query->favourite_for_id]);

                            if ($merchant != NULL) {
                                $return = [
                                    "id" => $query->id,
                                    "user_id" => $query->user_id,
                                    "name" => $merchant ? ($lang == 1 ? $merchant->business_name : $merchant->business_name_ar) : "",
                                    "rating" => $merchant->rating() <= 0 ? floatval(5) : floatval($merchant->rating()),
                                    "reviews_count" => count($merchant->Reviews()),
                                    "description" => $lang == 1 ? $merchant->description : $merchant->description_ar,
                                    "price" => null,
                                    "favourite_for_id" => $query->favourite_for_id,
                                    "favourite_type" => $query->favourite_type, //1-Products,2-Services,3-Store
                                    "status" => $query->status,
                                    "image" => $merchant->business_logo != "" ? 'uploads/merchant/' . $merchant->id . '/logo/large/' . $merchant->business_logo : "img/no-image.jpg",
                                ];
                            }
                        }
                    } else {
                        $errors_data = $query->getErrors();
                        foreach ($errors_data as $errors_dat) {
                            $errors[] = $errors_dat[0];
                        }
                    }
                }
            } else {
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(449, $name, $lang, $post, $post, 'favotites');
                Yii::$app->end();
            }
        } else {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, $post, 'favorites');
            Yii::$app->end();
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
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
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        $query = \common\models\Favorites::find()->where(['status' => 1, 'user_id' => $userId]); // Check the review is approved by admin
        if (isset($limit) && $limit != "") {
            $query->limit($limit);
        }
        if (isset($offset) && $offset != "") {
            $offset = ($offset - 1) * $limit;

            $query->offset($offset);
        }
        if (isset($Id) && $Id != "") { //url carriying ID
            $query->andWhere(['id' => $Id]);
            $models = $query->one();
            if ($models != NULL) {
                $return = [
                    "id" => $models->id,
                    "user_id" => $models->user_id,
                    "favourite_for_id" => $models->favourite_for_id,
                    "favourite_type" => $models->favourite_type, //1-Products,2-Services,3-Store
                    "status" => $models->status,
                ];
            }
        } else { // not carrying
            $models_data = $query->all();
            if ($models_data != NULL) {
                foreach ($models_data as $models) {
                    if ($models->favourite_type == 1 || $models->favourite_type == 2) {
                        $pmodel = \common\models\ProductsServices::findOne(['id' => $models->favourite_for_id]);
                        if ($pmodel != NULL) {
                            $result = [
                                "id" => $models->id,
                                "user_id" => $models->user_id,
                                "name" => $lang == 1 ? $pmodel->product_name_en : $pmodel->product_name_ar,
                                "rating" => $pmodel->rating() <= 0 ? floatval(5) : floatval($pmodel->rating()),
                                "reviews_count" => count($pmodel->Reviews()),
                                "description" => $lang == 1 ? $pmodel->short_description_en : $pmodel->short_description_ar,
                                "price" => Yii::$app->Products->priceConvert($pmodel, $lang),
                                "favourite_for_id" => $models->favourite_for_id,
                                "favourite_type" => $models->favourite_type, //1-Products,2-Services,3-Store
                                "status" => $models->status,
                                "image" => $pmodel->image != "" ? "uploads/products/" . base64_encode($pmodel->sku) . "/image/large/" . $pmodel->image : "img/no-image.jpg",
                            ];
                            array_push($return, $result);
                        }
                    } else if ($models->favourite_type == 3) {
                        $merchant = \common\models\Merchant::findOne(['id' => $models->favourite_for_id]);

                        if ($merchant != NULL) {
                            $result = [
                                "id" => $models->id,
                                "user_id" => $models->user_id,
                                "name" => $merchant ? ($lang == 1 ? $merchant->business_name : $merchant->business_name_ar) : "",
                                "rating" => $merchant->rating() <= 0 ? floatval(5) : floatval($merchant->rating()),
                                "reviews_count" => count($merchant->Reviews()),
                                "description" => $lang == 1 ? $merchant->description : $merchant->description_ar,
                                "price" => null,
                                "favourite_for_id" => $models->favourite_for_id,
                                "favourite_type" => $models->favourite_type, //1-Products,2-Services,3-Store
                                "status" => $models->status,
                                "image" => $merchant->business_logo != "" ? 'uploads/merchant/' . $merchant->id . '/logo/large/' . $merchant->business_logo : "img/no-image.jpg",
                            ];
                            array_push($return, $result);
                        }
                    }
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

}
