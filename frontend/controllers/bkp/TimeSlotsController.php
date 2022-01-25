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
class TimeSlotsController extends Controller {

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
                                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'gettimeslot');
                                    \Yii::$app->response->data = $array;
                                } else if ($data['data'] != NULL) { //success
                                    $transaction->commit();
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'gettimeslot');
                                } else { // NO data Found based  on request
                                    $array = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'gettimeslot');
                                    $array['message'] = Yii::$app->ManageRequest->getMessage('not_time_slots_found_given_date', $lang);
                                    \Yii::$app->response->data = $array;
                                }
                            } else { // NO data Found based  on request
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'promotion');
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
        $params = ["date", "id"]; // date is the service required data, id is the product/service ID
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
            $current_day = date('Y-m-d');
//Check the requested date is past date
            if (date('Y-m-d', strtotime($post['date'])) < $current_day) {

                // $errors['date'] = "Invalid Date Choosen";
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(455, $name, $lang, $post, (object) [], 'cart');
                Yii::$app->end();
            }
            if ($errors == NULL) { // Any Error in the post data
                $product = \common\models\ProductsServices::find()->where(['id' => $post['id']])->one();
                if ($product != NULL) {
                    if ($product->type == 2 || $product->type == 3) {

                        $day = date('l', strtotime($post['date']));
//Getting Time slot details for the requested day
                        $get_week_day = \common\models\WeekDaysAvailability::find()->where(['merchant_id' => $product->merchant_id, 'day' => $day, 'availability' => 1])->one();
                        if ($get_week_day != NULL) {
//Getting Any Disable Time slots available in that day
                            $get_disable_slots = \common\models\DisableSlots::find()->where("slot_from >= '" . $get_week_day->available_from . "' AND slot_to <= '" . $get_week_day->available_to . "'")->andWhere(['merchant_id' => $product->merchant_id, 'day' => $get_week_day->id])->all();
                            $intervals = [];
                            if ($get_week_day->available_from != NULL && $get_week_day->available_to != NULL && $get_week_day->slot_interval != NULL) {
                                //Getting Available Time Slots As Array
                                $intervals = Yii::$app->ManageRequest->getTimeSlots($get_week_day->available_from, $get_week_day->available_to, $get_week_day->slot_interval, $get_disable_slots);
                            }
                            if ($intervals != NULL) {
                                foreach ($intervals as $key => $val) {

                                    if ($key < array_key_last($intervals)) {
                                        $check_disable = \common\models\DisableSlots::find()->where(['day' => $get_week_day->id, 'merchant_id' => $get_week_day->merchant_id, 'status' => 1, 'slot_from' => date('H:i', strtotime($intervals[$key])), 'slot_to' => date('H:i', strtotime($intervals[$key + 1]))])->one();
                                        if ($check_disable == NULL) {
                                            array_push($return, [
                                                'key' => $intervals[$key] . '-' . $intervals[$key + 1],
                                                'value' => $intervals[$key] . '-' . $intervals[$key + 1],
                                                    ]
                                            );
                                        }
                                    }
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

}
