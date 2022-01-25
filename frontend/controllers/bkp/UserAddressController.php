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
class UserAddressController extends Controller {

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
//                    'actions' => ['get', 'post', 'index', 'put', 'delete'],
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

        $url = filter_input(INPUT_SERVER, 'REQUEST_URI');
        if (strpos($url, '/ar') !== false) {
            Yii::$app->session['lang'] = 'ar';
        } else {
            Yii::$app->session['lang'] = 'en';
        }
    }

    public function actionIndex() {

        $name = "Profile";
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
                        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
                        $usermodel = \common\models\User::findOne(['id' => $userId]);
                        if ($usermodel != NULL) { //check usrr exist
                            if ($usermodel->user_type != 3) { // check it is not a guest
                                $action = strtolower($_SERVER['REQUEST_METHOD']); // Getting action from request header
                                $data = $this->$action($headers, $post); // Call respective action with post data and headers
                                if ($data != NULL) { // check the result have value
                                    if ($data['error'] != NULL) { // Error Found on the reques
                                        $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'user_address');
                                        \Yii::$app->response->data = $array;
                                    } else if ($data['data'] != NULL) { //success
                                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'user_address');
                                    } else { // NO data Found based  on request
                                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'user_address');
                                    }
                                } else { // NO data Found based  on request
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'user_address');
                                }
                            } else { //Un autherised Auth Token
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'user_address');
                            }
                        } else { //Un autherised Auth Token
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'user_address');
                        }
                    } else { //Un autherised Auth Token
                        \Yii::$app->response->statusCode = 401;
                        $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'user_address');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Auth token not provided
                    \Yii::$app->response->statusCode = 401;
                    $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'user_address');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'user_address');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'user_address');
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
        $errors = [];
        $return = [];
        $Id = Yii::$app->request->get('id'); // Getting review id from url
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        $query = \common\models\UserAddress::find()->where(['user_id' => $userId]); // Check the review is approved by admin
//Building query based on input get params
        if (isset($limit) && $limit != "") {
            $query->limit($limit);
        }
        if (isset($offset) && $offset != "") {
            $offset = ($offset - 1) * $limit;
            $query->offset($offset);
        }

        if (isset($Id) && $Id != "") { //url carriying ID
            $query->andWhere(['id' => $Id]);
            $model = $query->one();
            if ($model != NULL) {
                $return = [
                    "id" => $model->id,
                    "full_name" => $model->first_name,
//                    "last_name" => $model->last_name,
                    "country_id" => $model->country,
                    "country_name" => $lang == 1 ? $model->country0->country_name : ($model->country0->country_name_ar != "" ? $model->country0->country_name_ar : $model->country0->country_name),
                    "state_id" => $model->state,
                    "state_name" => $model->state != "" ? ($lang == 1 ? $model->state0->state_name : ($model->state0->state_name_ar != "" ? $model->state0->state_name_ar : $model->state0->state_name)) : "",
                    "city_id" => $model->city,
                    "city_name" => $lang == 1 ? $model->city0->name_en : ($model->city0->name_ar != "" ? $model->city0->name_ar : $model->city0->name_en),
                    "streat_address" => $model->streat_address,
                    "postcode" => $model->postcode,
                    "phone_number" => $model->phone_number,
                    "default_billing_address" => $model->default_billing_address,
                    "default_shipping_address" => $model->default_shipping_address,
                    "email" => $model->email,
                ];
            }
        } else { // not carrying
            $models_data = $query->all();
            if ($models_data != NULL) {
                foreach ($models_data as $model) {
                    $result = [
                        "id" => $model->id,
                        "user_id" => $model->user_id,
                        "full_name" => $model->first_name,
//                        "last_name" => $model->last_name,
                        "country_id" => $model->country,
                        "country_name" => $lang == 1 ? $model->country0->country_name : ($model->country0->country_name_ar != "" ? $model->country0->country_name_ar : $model->country0->country_name),
                        "state_id" => $model->state,
                        "state_name" => $model->state != "" ? ($lang == 1 ? $model->state0->state_name : ($model->state0->state_name_ar != "" ? $model->state0->state_name_ar : $model->state0->state_name)) : "",
                        "city_id" => $model->city,
                        "city_name" => $lang == 1 ? $model->city0->name_en : ($model->city0->name_ar != "" ? $model->city0->name_ar : $model->city0->name_en),
                        "streat_address" => $model->streat_address,
                        "postcode" => $model->postcode,
                        "phone_number" => $model->phone_number,
//                                    "default_billing_address" => $model->default_billing_address,
//                                    "default_shipping_address" => $model->default_shipping_address,
                        "email" => $model->email,
                    ];
                    array_push($return, $result);
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

    public function post($headers, $post = []) { // post operation for creating User Address
        $name = "User Address";
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $finaldata = [];
        $return = [];
        $errors = [];
        $params = ['country', 'city', 'street_address', 'zipcode'];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        if (isset($post) && $post != NULL) { // checking post data exist
            if ($params != NULL) {
                foreach ($params as $param) {
                    if (isset($post[$param])) {
                        if ($post[$param] == NULL || $post[$param] == "") {
                            $errors[$param] = $post[$param];
                        }
                    } else {
                        $errors[$param] = NULL;
                    }
                }
            }
            if ($errors == NULL) { // Any Error in the post data
//                $model = new \common\models\UserAddress();
                if (isset($post['country']) && $post['country'] != "") {
                    $check_country = \common\models\Country::findOne(['id' => $post['country']]);
                    if ($check_country == NULL) {
                        $array = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'user_address');
                        \Yii::$app->response->data = $array;
                        Yii::$app->end();
                    }
                }
                if (isset($post['state']) && $post['state'] != "") {
                    $check_state = \common\models\States::findOne(['id' => $post['state'], 'country_id' => $post['country']]);
                    if ($check_state == NULL) {
                        $array = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'user_address');
                        \Yii::$app->response->data = $array;
                        Yii::$app->end();
                    }
                }
                if (isset($post['city']) && $post['city'] != "") {
                    $check_state = \common\models\City::findOne(['id' => $post['city'], 'state' => $post['state'], 'country' => $post['country']]);
                    if ($check_state == NULL) {
                        $array = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'user_address');
                        \Yii::$app->response->data = $array;
                        Yii::$app->end();
                    }
                }


                $model = \common\models\UserAddress::findOne(['user_id' => $userId]);
                //setting model Attributes
                if ($model == NULL) {
                    $model = new \common\models\UserAddress();
                    $model->user_id = $userId;
                }
                $user = \common\models\User::find()->where(['id' => $userId])->one();
                if ($user != NULL) {
                    $model->first_name = $user->first_name;
                    $model->last_name = $user->last_name;
                    $model->country = $post['country'];
                    $model->state = $post['state'];
                    $model->city = $post['city'];
                    $model->streat_address = $post['street_address'];
                    $model->postcode = $post['zipcode'];
                    $model->phone_number = $user->mobile_number;

                    // $model->default_billing_address = $post['default_billing_address']; // value is either 1(Default Address for billing) or 0
                    //   $model->default_shipping_address = $post['default_shipping_address']; // value is either 1(Default Address for shipping) or 0
                    $model->default_billing_address = 1; // value is either 1(Default Address for billing) or 0
                    $model->default_shipping_address = 1; // value is either 1(Default Address for shipping) or 0
                    $model->email = $user->email;
                    $model->updated_by = $userId; // user side created by user itself
                    $model->created_by = $userId; // user side updated by user itself
                    $model->created_by_type = 1; // user side updated by user itself
                    $model->updated_by_type = 1; // user side updated by user itself
                    $transaction = Yii::$app->db->beginTransaction();
                }
                try {
//                        if ($post['default_billing_address'] == 1) {
//                            \common\models\UserAddress::updateAll(['default_billing_address' => 0]);
//                        }
//                        if ($post['default_shipping_address'] == 1) {
//                            \common\models\UserAddress::updateAll(['default_shipping_address' => 0]);
//                        }
                    if ($model->save()) { // Creating notification is success
                        $query = \common\models\UserAddress::find()->where(['user_id' => $userId]); // Check the review is approved by admin
                        $models_data = $query->all();
                        if ($models_data != NULL) {
                            $user = \common\models\User::find()->where(['id' => $userId])->one();
                            if ($user != NULL) {
//                                foreach ($models_data as $model) {
//                                    $result = [
//                                        "id" => $model->id,
//                                        "user_id" => $model->user_id,
//                                        "first_name" => $model->first_name,
//                                        "last_name" => $model->last_name,
//                                        "country_id" => $model->country,
//                                        "country_name" => $lang == 1 ? $model->country0->country_name : ($model->country0->country_name_ar != "" ? $model->country0->country_name_ar : $model->country0->country_name),
//                                        "state_id" => $model->state,
//                                        "state_name" => $model->state != "" ? ($lang == 1 ? $model->state0->state_name : ($model->state0->state_name_ar != "" ? $model->state0->state_name_ar : $model->state0->state_name)) : "",
//                                        "city_id" => $model->city,
//                                        "city_name" => $lang == 1 ? $model->city0->name_en : ($model->city0->name_ar != "" ? $model->city0->name_ar : $model->city0->name_en),
//                                        "streat_address" => $model->streat_address,
//                                        "postcode" => $model->postcode,
//                                        "phone_number" => $model->phone_number,
////                                    "default_billing_address" => $model->default_billing_address,
////                                    "default_shipping_address" => $model->default_shipping_address,
//                                        "email" => $user->email,
//                                    ];
//                                    array_push($return, $result);
//                                }
                                $return = [
                                    "id" => $model->id,
                                    "user_id" => $model->user_id,
                                    "first_name" => $model->first_name,
                                    "last_name" => $model->last_name,
                                    "country_id" => $model->country,
                                    "country_name" => $lang == 1 ? $model->country0->country_name : ($model->country0->country_name_ar != "" ? $model->country0->country_name_ar : $model->country0->country_name),
                                    "state_id" => $model->state,
                                    "state_name" => $model->state != "" ? ($lang == 1 ? $model->state0->state_name : ($model->state0->state_name_ar != "" ? $model->state0->state_name_ar : $model->state0->state_name)) : "",
                                    "city_id" => $model->city,
                                    "city_name" => $lang == 1 ? $model->city0->name_en : ($model->city0->name_ar != "" ? $model->city0->name_ar : $model->city0->name_en),
                                    "streat_address" => $model->streat_address,
                                    "postcode" => $model->postcode,
                                    "phone_number" => $model->phone_number,
//                                    "default_billing_address" => $model->default_billing_address,
//                                    "default_shipping_address" => $model->default_shipping_address,
                                    "email" => $user->email,
                                ];
                            }
                        }

                        $transaction->commit();
                    } else { // model save is error
                        $transaction->rollBack();
                        $errors_data = $model->getErrors();
                        foreach ($errors_data as $errors_dat) {
                            $errors[] = $errors_dat[0];
                        }
                    }
                }//catch exception
                catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        } else {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(449, $name, $lang, $post, $post, 'reviews');
            Yii::$app->end();
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

    public function put($headers, $post = []) { // post operation for creating User Address
        $name = "User Address";
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $finaldata = [];
        $return = [];
        $errors = [];
        $params = ['id', 'country', 'city', 'streat_address', 'zipcode'];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        if (isset($post) && $post != NULL) { // checking post data exist
            if ($params != NULL) {
                foreach ($params as $param) {
                    if (isset($post[$param])) {
                        if ($post[$param] == NULL || $post[$param] == "") {
                            $errors[$param] = $post[$param];
                        }
                    } else {
                        $errors[$param] = NULL;
                    }
                }
            }
            if ($errors == NULL) { // Any Error in the post data
                $model = \common\models\UserAddress::findOne(['id' => $post['id'], 'user_id' => $userId]);
                //setting model Attributes
                if ($model != NULL) {
                    $user = \common\models\User::find()->where(['id' => $userId])->one();
                    if ($user != NULL) {
                        $model->user_id = $userId;
                        $model->first_name = $user->first_name;
//                    $model->last_name = $post['last_name'];
                        $model->country = $post['country'];
                        $model->state = $post['state'];
                        $model->city = $post['city'];
                        $model->streat_address = $post['streat_address'];
                        $model->postcode = $post['postcode'];
                        $model->phone_number = $user->phone_number;
                        $model->default_billing_address = $post['default_billing_address']; // value is either 1(Default Address for billing) or 0
                        $model->default_shipping_address = $post['default_shipping_address']; // value is either 1(Default Address for shipping) or 0
                        $model->email = $user->email;
                        $model->updated_by = $userId; // user side created by user itself
                        $model->created_by = $userId; // user side updated by user itself
                        $model->created_by_type = 1; // //1-User , 2- Admin, 3-Merchant, 4-Franchise
                        $model->updated_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    }
                    $transaction = Yii::$app->db->beginTransaction();

                    if ($post['default_billing_address'] == 1) {
                        \common\models\UserAddress::updateAll(['default_billing_address' => 0]);
                    }
                    if ($post['default_shipping_address'] == 1) {
                        \common\models\UserAddress::updateAll(['default_shipping_address' => 0]);
                    }
                    if ($model->save()) { // Creating notification is success
                        $query = \common\models\UserAddress::find()->where(['user_id' => $userId]); // Check the review is approved by admin
                        $models_data = $query->all();
                        if ($models_data != NULL) {
                            foreach ($models_data as $model) {
                                $result = [
                                    "id" => $model->id,
                                    "first_name" => $model->first_name,
                                    "last_name" => $model->last_name,
//                                    "last_name" => $model->last_name,
                                    "country_id" => $model->country,
                                    "country_name" => $lang == 1 ? $model->country0->country_name : ($model->country0->country_name_ar != "" ? $model->country0->country_name_ar : $model->country0->country_name),
                                    "state_id" => $model->state,
                                    "state_name" => $model->state != "" ? ($lang == 1 ? $model->state0->state_name : ($model->state0->state_name_ar != "" ? $model->state0->state_name_ar : $model->state0->state_name)) : "",
                                    "city_id" => $model->city,
                                    "city_name" => $lang == 1 ? $model->city0->name_en : ($model->city0->name_ar != "" ? $model->city0->name_ar : $model->city0->name_en),
                                    "streat_address" => $model->streat_address,
                                    "postcode" => $model->postcode,
                                    "phone_number" => $model->phone_number,
                                    "default_billing_address" => $model->default_billing_address,
                                    "default_shipping_address" => $model->default_shipping_address,
                                    "email" => $model->email,
                                ];
                                array_push($return, $result);
                            }
                        }
                        $transaction->commit();
                    } else { // model save is error
                        $errors_data = $model->getErrors();
                        foreach ($errors_data as $errors_dat) {
                            $errors[] = $errors_dat[0];
                        }
                    }
                }
            }
        } else {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(449, $name, $lang, $post, $post, 'user_address');
            Yii::$app->end();
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

    public function delete($headers, $post = []) { // Geting Data
        $name = "Delete User Address";
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $finaldata = [];
        $return = [];
        $errors = [];
        $Id = Yii::$app->request->get('id'); // Getting User Address id from url
        //Building query based on input get params
        // Add New Product to User Address
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        if (!isset($Id) || $Id == "0" || $Id == "") {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'user_address');
            Yii::$app->end();
        }
        $transaction = Yii::$app->db->beginTransaction();

        $model = \common\models\UserAddress::find()->where(['id' => $Id, 'user_id' => $userId])->one(); // Getting The User Address items
        if ($model != NULL) {
            if ($model->delete()) {
                $transaction->commit();
                $getquery = \common\models\UserAddress::find()->where(['user_id' => $userId]); // Getting The User Address items
                $models_data = $getquery->all();
                if ($models_data != NULL) {
                    foreach ($models_data as $model) {
                        $result = [
                            "id" => $model->id,
                            "first_name" => $model->first_name,
                            "last_name" => $model->last_name,
                            "country_id" => $model->country,
                            "country_name" => $lang == 1 ? $model->country0->country_name : ($model->country0->country_name_ar != "" ? $model->country0->country_name_ar : $model->country0->country_name),
                            "state_id" => $model->state,
                            "state_name" => $model->state != "" ? ($lang == 1 ? $model->state0->state_name : ($model->state0->state_name_ar != "" ? $model->state0->state_name_ar : $model->state0->state_name)) : "",
                            "city_id" => $model->city,
                            "city_name" => $lang == 1 ? $model->city0->name_en : ($model->city0->name_ar != "" ? $model->city0->name_ar : $model->city0->name_en),
                            "streat_address" => $model->streat_address,
                            "postcode" => $model->postcode,
                            "phone_number" => $model->phone_number,
                            "default_billing_address" => $model->default_billing_address,
                            "default_shipping_address" => $model->default_shipping_address,
                            "email" => $model->email,
                        ];
                        array_push($return, $result);
                    }
                }
                $array = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $post, 'user_address');
                $array['data'] = $return;
                $array['message'] = Yii::$app->ManageRequest->getMessage('item_delete', $lang);
                \Yii::$app->response->data = $array;
                Yii::$app->end();
            } else {
                $transaction->rollBack();
                $errors_data = $model->getErrors();
                foreach ($errors_data as $errors_dat) {
                    $errors[] = $errors_dat[0];
                }
            }
        } else {
            $errors['id'] = Yii::$app->ManageRequest->getMessage('item_not_found', $lang);
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

}
