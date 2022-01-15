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
class UpdateNewPasswordController extends Controller {

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
                                        $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'set_new_password');
                                        \Yii::$app->response->data = $array;
                                    } else if ($data['data'] != NULL) { //success
                                        $transaction->commit();
                                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'set_new_password');
                                    } else { // NO data Found based  on request
                                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'set_new_password');
                                    }
                                } else { // NO data Found based  on request
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'set_new_password');
                                }
                            } else { //Un autherised Auth Token
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'set_new_password');
                            }
                        } else { //Un autherised Auth Token
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'set_new_password');
                        }
                    } else { //Un autherised Auth Token
                        $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'set_new_password');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Auth token not provided
                    $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'set_new_password');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'set_new_password');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'set_new_password');
        }
    }

    public function post($headers, $post = []) { // post operation for creating set_new_password
        $name = "Edit Profile";
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $finaldata = [];
        $address_array = [];
        $return = [];
        $errors = [];
        $notif_type_array = [];
        $params = ['old_password', 'new_password'];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        if (isset($post) && $post != NULL) { // checking post data exist
            if ($params != NULL) {

                if (isset($post['old_password']) && $post['old_password'] != '') {
                    if (base64_decode($post['old_password'], true)) { //Checking the given password is base 64 encoded or not
                        $old_password = base64_decode($post['old_password']);
                    } else {
                        $old_password = "";
                        $errors['old_password'] = $post['old_password'];
                    }
                } else {
                    $old_password = "";
                    $errors['old_password'] = $post['old_password'];
                }
                if (isset($post['new_password']) && $post['new_password'] != '') {
                    if (base64_decode($post['new_password'], true)) { //Checking the given password is base 64 encoded or not
                        $new_password = base64_decode($post['new_password']);
                    } else {
                        $new_password = "";
                        $errors['new_password'] = $post['new_password'];
                    }
                } else {
                    $new_password = "";
                    $errors['new_password'] = $post['new_password'];
                }
            }
            if ($errors == NULL) { // Any Error in the post data
                $models = \common\models\User::findOne($userId);

                //setting model Attributes
                if ($models != NULL) {
                    $transaction = Yii::$app->db->beginTransaction();
                    if ($models->account_type == 1) {

                        if ($models->validatePassword($old_password)) {
                            $models->password = Yii::$app->security->generatePasswordHash($new_password);
                            if ($models->save()) { // Creating notification is success
                                if ($models->user_type == 2) {
                                    $merchant = \common\models\Merchant::find()->where(['user_id' => $models->id])->one(); // check a user exist with give email
                                    if ($merchant != NULL) { // exist
                                        if ($merchant->save()) { // model going to save
                                            $merchant->password = Yii::$app->security->generatePasswordHash($new_password);
                                        } else {
                                            $errors_data = $merchant->getErrors();
                                            foreach ($errors_data as $errors_dat) {
                                                $errors[] = $errors_dat[0];
                                            }
                                        }
                                    }
                                }
                                \common\models\Authentication::deleteAll(['AND',
                                    ['NOT', ['auth_key' => $headers ['authToken']]],
                                    ['user_id' => $userId]
                                ]);
                                $query = \common\models\UserAddress::find()->where(['user_id' => $userId]); // Check the review is approved by admin
                                $model = $query->one();
                                if ($model != NULL) {
//                                    foreach ($models_data as $model) {
//                                        $add = [
//                                            "id" => $model->id,
//                                            "first_name" => $model->first_name,
//                                            "last_name" => $model->last_name,
//                                            "country_id" => $model->country,
//                                            "country_name" => $lang == 1 ? $model->country0->country_name : ($model->country0->country_name_ar != "" ? $model->country0->country_name_ar : $model->country0->country_name),
//                                            "state_id" => $model->state,
//                                            "state_name" => $model->state != "" ? ($lang == 1 ? $model->state0->state_name : ($model->state0->state_name_ar != "" ? $model->state0->state_name_ar : $model->state0->state_name)) : "",
//                                            "city_id" => $model->city,
//                                            "city_name" => $lang == 1 ? $model->city0->name_en : ($model->city0->name_ar != "" ? $model->city0->name_ar : $model->city0->name_en),
//                                            "streat_address" => $model->streat_address,
//                                            "postcode" => $model->postcode,
//                                            "phone_number" => $model->phone_number,
//                                            "default_billing_address" => $model->default_billing_address,
//                                            "default_shipping_address" => $model->default_shipping_address,
//                                            "email" => $model->email,
//                                        ];
//                                        array_push($address_array, $add);
//                                    }
                                    $address_array = [
                                        "id" => $model->id,
                                        "first_name" => $model->first_name,
                                        "last_name" => $model->last_name,
                                        "country_id" => $model->country,
                                        "country_name" => $lang == 1 ? $model->country0->country_name : ($model->country0->country_name_ar != "" ? $model->country0->country_name_ar : $model->country0->country_name),
                                        "state_id" => $model->state,
                                        "state_name" => $model->state != "" ? ($lang == 1 ? $model->state0->state_name : ($model->state0->state_name_ar != "" ? $model->state0->state_name_ar : $model->state0->state_name)) : "",
                                        "city_id" => $model->city,
                                        "city_name" => $lang == 1 ? $model->city0->name_en : ($model->city0->name_ar != "" ? $model->city0->name_ar : $model->city0->name_en),
                                        "street_address" => $model->streat_address,
                                        "zipcode" => $model->postcode,
                                        "phone_number" => $model->phone_number,
//                    "default_billing_address" => $model->default_billing_address,
//                    "default_shipping_address" => $model->default_shipping_address,
                                        "email" => $model->email,
                                    ];
                                }
                                $newquery = \common\models\NotificationType::find()->where(['status' => 1]); // Check the review is approved by admin
                                $models_data = $newquery->all();
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
                                $return = [
                                    "id" => $models->id,
                                    "first_name" => $models->first_name,
                                    "last_name" => $models->last_name,
                                    "email" => $models->email,
                                    "account_type" => $models->account_type,
                                    'profile_image' => $models->profile_image != "" ? "uploads/user/" . $models->id . "/" . $models->profile_image : "img/no-image.jpg",
                                    "mobile_number" => $models->mobile_number,
                                    "user_type" => $models->user_type, // 1-User,2-Merchant
                                    "app_lang_id" => $models->app_lang_id, // 1-En,2-Ar
                                    "user_address" => $address_array != NULL ? $address_array : null,
                                    "notification_types" => $notif_type_array
                                ];
                                $transaction->commit(); // Commit The Transaction
                            } else { // model save is error
                                $errors_data = $models->getErrors();
                                foreach ($errors_data as $errors_dat) {
                                    $errors[] = $errors_dat[0];
                                }
                                $transaction->rollBack(); // Commit The Transaction
                            }
                        } else {
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(453, $name, $lang, $post, (object) [], 'set_new_password');
                            Yii::$app->end();
                            $transaction->rollBack(); // Commit The Transaction
                        }
                    } else {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(453, $name, $lang, $post, $post, 'set_new_password');
                        Yii::$app->end();
                        $transaction->rollBack(); // Commit The Transaction
                    }
                }
            }
        } else {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(449, $name, $lang, $post, $post, 'set_new_password');
            Yii::$app->end();
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

}
