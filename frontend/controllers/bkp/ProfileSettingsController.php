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
class ProfileSettingsController extends Controller {

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
                        $transaction = Yii::$app->db->beginTransaction();
                        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
                        $usermodel = \common\models\User::findOne(['id' => $userId]);

                        if ($usermodel != NULL) { //check usrr exist
                            if ($usermodel->user_type != 3) { // check it is not a guest
                                $action = strtolower($_SERVER['REQUEST_METHOD']); // Getting action from request header
                                $data = $this->$action($headers, $post); // Call respective action with post data and headers
                                if ($data != NULL) { // check the result have value
                                    if ($data['error'] != NULL) { // Error Found on the reques
                                        $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'profile_edit');
                                        \Yii::$app->response->data = $array;
                                    } else if ($data['data'] != NULL) { //success
                                        $transaction->commit();
                                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'profile_edit');
                                    } else { // NO data Found based  on request
                                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'profile_edit');
                                    }
                                } else { // NO data Found based  on request
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'profile_edit');
                                }
                            } else { //Un autherised Auth Token
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'profile_edit');
                            }
                        } else { //Un autherised Auth Token
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'profile_edit');
                        }
                    } else { //Un autherised Auth Token
                        \Yii::$app->response->statusCode = 401;
                        $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'profile_edit');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Auth token not provided
                    \Yii::$app->response->statusCode = 401;
                    $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'profile_edit');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'profile_edit');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'profile_edit');
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
        $notif_type_array = [];
        $errors = [];
        $address_array = [];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        $models = \common\models\User::find()->where(['id' => $userId])->one(); // Check the review is approved by admin
//Building query based on input get params
        if ($models != NULL) {
            $query = \common\models\NotificationType::find()->where(['status' => 1]); // Check the review is approved by admin
            $models_data = $query->all();
            if ($models_data != NULL) {
                foreach ($models_data as $model) {
                    $check_user_notif_status = \common\models\UserNotification::find()->where(['user_id' => $userId, 'status' => 1, 'notification_type' => $model->id])->one(); // Check the review is approved by admin

                    $add = [
                        "id" => $model->id,
                        "name" => $lang == 1 ? $model->name : $model->name_ar,
                        "status" => $check_user_notif_status != NULL ? 1 : 0,
                        "image" => $model->image != "" ? "uploads/notification-type/" . $model->id . "/" . $model->image : "img/no-image.jpg",
                    ];
                    array_push($notif_type_array, $add);
                }
            }
            $addressModel = \common\models\UserAddress::find()->where(['user_id' => $userId])->one(); // Check the review is approved by admin

            if ($addressModel != NULL) {
//                foreach ($models_data as $model) {
//                    $address_array = [
//                        "id" => $model->id,
//                        "first_name" => $model->first_name,
//                        "last_name" => $model->last_name,
//                        "country_id" => $model->country,
//                        "country_name" => $lang == 1 ? $model->country0->country_name : ($model->country0->country_name_ar != "" ? $model->country0->country_name_ar : $model->country0->country_name),
//                        "state_id" => $model->state,
//                        "state_name" => $model->state != "" ? ($lang == 1 ? $model->state0->state_name : ($model->state0->state_name_ar != "" ? $model->state0->state_name_ar : $model->state0->state_name)) : "",
//                        "city_id" => $model->city,
//                        "city_name" => $lang == 1 ? $model->city0->name_en : ($model->city0->name_ar != "" ? $model->city0->name_ar : $model->city0->name_en),
//                        "streat_address" => $model->streat_address,
//                        "postcode" => $model->postcode,
//                        "phone_number" => $model->phone_number,
//                        "default_billing_address" => $model->default_billing_address,
//                        "default_shipping_address" => $model->default_shipping_address,
//                        "email" => $model->email,
//                    ];
//                    array_push($address_array, $add);
//                }

                $address_array = [
                    "id" => $addressModel->id,
                    "first_name" => $addressModel->first_name,
                    "last_name" => $addressModel->last_name,
                    "country_id" => $addressModel->country,
                    "country_name" => $lang == 1 ? $addressModel->country0->country_name : ($addressModel->country0->country_name_ar != "" ? $addressModel->country0->country_name_ar : $addressModel->country0->country_name),
                    "state_id" => $addressModel->state,
                    "state_name" => $addressModel->state != "" ? ($lang == 1 ? $addressModel->state0->state_name : ($addressModel->state0->state_name_ar != "" ? $addressModel->state0->state_name_ar : $addressModel->state0->state_name)) : "",
                    "city_id" => $addressModel->city,
                    "city_name" => $lang == 1 ? $addressModel->city0->name_en : ($addressModel->city0->name_ar != "" ? $addressModel->city0->name_ar : $addressModel->city0->name_en),
                    "street_address" => $addressModel->streat_address,
                    "zipcode" => $addressModel->postcode,
                    "phone_number" => $addressModel->phone_number,
//                    "default_billing_address" => $addressModel->default_billing_address,
//                    "default_shipping_address" => $addressModel->default_shipping_address,
                    "email" => $addressModel->email,
                ];
            }
            $return = [
                "id" => $models->id,
                "first_name" => $models->first_name,
                "last_name" => $models->last_name,
                "email" => $models->email,
                "account_type" => $models->account_type,
                'profile_image' => $models->profile_image != "" ? "uploads/users/" . $models->id . "/" . $models->profile_image : "img/no-image.jpg",
                "mobile_number" => $models->mobile_number,
                "user_type" => $models->user_type, // 1-User,2-Merchant
                "app_lang_id" => $models->app_lang_id, // 1-En,2-Ar
                "user_address" => $address_array != NULL ? $address_array : null,
                "notification_types" => $notif_type_array
            ];
        }

        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

    public function post($headers, $post = []) { // post operation for creating reviews
        $name = "Edit Profile";
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $finaldata = [];
        $eligibility = [];
        $return = [];
        $errors = [];
        $notif_type_array = [];
        $address_array = [];

        $params = ['notification_type_id', 'status'];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        if (isset($post) && $post != NULL) { // checking post data exist
            if ($params != NULL) {
                foreach ($params as $param) {
                    if (isset($post[$param])) {
//                        if ($post[$param] == NULL || $post[$param] == "") {
//                            $errors[$param] = $post[$param];
//                        }
                    } else {
                        $errors[$param] = NULL;
                    }
                }
            }
            if ($errors == NULL) { // Any Error in the post data
                $models = \common\models\User::findOne($userId);

                //setting model Attributes
                if ($models != NULL) {
                    $check_user_notif_status = \common\models\UserNotification::find()->where(['user_id' => $userId, 'notification_type' => $post['notification_type_id']])->one(); // Check the review is approved by admin
                    if ($check_user_notif_status != NULL) {
                        $notif_model = $check_user_notif_status;
                    } else {
                        $notif_model = new \common\models\UserNotification();
                        $notif_model->user_id = $userId;
                        if (isset($post['notification_type_id']) && $post['notification_type_id'] > 0) {
                            $notif_model->notification_type = $post['notification_type_id'];
                        }
                    }
                    if (isset($post['status']) && ($post['status'] == 1 || $post['status'] == 0 )) {
                        $notif_model->status = $post['status'];
                    }
//                    $models->app_lang_id = $post['app_lang_id'];
                    $transaction = Yii::$app->db->beginTransaction();

                    if ($models->save()) { // Creating notification is success
                        if ($notif_model->save()) {
                            $query = \common\models\NotificationType::find()->where(['status' => 1]); // Check the review is approved by admin
                            $models_data = $query->all();
                            if ($models_data != NULL) {
                                foreach ($models_data as $model) {
                                    $check_user_notif_status = \common\models\UserNotification::find()->where(['user_id' => $userId, 'status' => 1, 'notification_type' => $model->id])->one(); // Check the review is approved by admin

                                    $add = [
                                        "id" => $model->id,
                                        "name" => $lang == 1 ? $model->name : $model->name_ar,
                                        "status" => $check_user_notif_status != NULL ? 1 : 0,
                                        "image" => $model->image != "" ? "uploads/notification-type/" . $model->id . "/" . $model->image : "img/no-image.jpg",
                                    ];
                                    array_push($notif_type_array, $add);
                                }
                            }
                            $addressModel = \common\models\UserAddress::find()->where(['user_id' => $userId])->one(); // Check the review is approved by admin

                            if ($addressModel != NULL) {
//                foreach ($models_data as $model) {
//                    $address_array = [
//                        "id" => $model->id,
//                        "first_name" => $model->first_name,
//                        "last_name" => $model->last_name,
//                        "country_id" => $model->country,
//                        "country_name" => $lang == 1 ? $model->country0->country_name : ($model->country0->country_name_ar != "" ? $model->country0->country_name_ar : $model->country0->country_name),
//                        "state_id" => $model->state,
//                        "state_name" => $model->state != "" ? ($lang == 1 ? $model->state0->state_name : ($model->state0->state_name_ar != "" ? $model->state0->state_name_ar : $model->state0->state_name)) : "",
//                        "city_id" => $model->city,
//                        "city_name" => $lang == 1 ? $model->city0->name_en : ($model->city0->name_ar != "" ? $model->city0->name_ar : $model->city0->name_en),
//                        "streat_address" => $model->streat_address,
//                        "postcode" => $model->postcode,
//                        "phone_number" => $model->phone_number,
//                        "default_billing_address" => $model->default_billing_address,
//                        "default_shipping_address" => $model->default_shipping_address,
//                        "email" => $model->email,
//                    ];
//                    array_push($address_array, $add);
//                }

                                $address_array = [
                                    "id" => $addressModel->id,
                                    "first_name" => $addressModel->first_name,
                                    "last_name" => $addressModel->last_name,
                                    "country_id" => $addressModel->country,
                                    "country_name" => $lang == 1 ? $addressModel->country0->country_name : ($addressModel->country0->country_name_ar != "" ? $addressModel->country0->country_name_ar : $addressModel->country0->country_name),
                                    "state_id" => $addressModel->state,
                                    "state_name" => $addressModel->state != "" ? ($lang == 1 ? $addressModel->state0->state_name : ($addressModel->state0->state_name_ar != "" ? $addressModel->state0->state_name_ar : $addressModel->state0->state_name)) : "",
                                    "city_id" => $addressModel->city,
                                    "city_name" => $lang == 1 ? $addressModel->city0->name_en : ($addressModel->city0->name_ar != "" ? $addressModel->city0->name_ar : $addressModel->city0->name_en),
                                    "street_address" => $addressModel->streat_address,
                                    "zipcode" => $addressModel->postcode,
                                    "phone_number" => $addressModel->phone_number,
//                    "default_billing_address" => $addressModel->default_billing_address,
//                    "default_shipping_address" => $addressModel->default_shipping_address,
                                    "email" => $addressModel->email,
                                ];
                            }
                            $return = [
                                "id" => $models->id,
                                "first_name" => $models->first_name,
                                "last_name" => $models->last_name,
                                "email" => $models->email,
                                "account_type" => $models->account_type,
                                'profile_image' => $models->profile_image != "" ? "uploads/users/" . $models->id . "/" . $models->profile_image : "img/no-image.jpg",
                                "mobile_number" => $models->mobile_number,
                                "user_type" => $models->user_type, // 1-User,2-Merchant
                                "app_lang_id" => $models->app_lang_id, // 1-En,2-Ar
                                "user_address" => $address_array != NULL ? $address_array : null,
                                "notification_types" => $notif_type_array
                            ];
                        }
                        $transaction->commit();
                    } else { // model save is error
                        $errors_data = $models->getErrors();
                        foreach ($errors_data as $errors_dat) {
                            $errors[] = $errors_dat[0];
                        }
                        $transaction->rollBack();
                    }
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

}
