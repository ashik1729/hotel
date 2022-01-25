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
class PaymentController extends Controller {

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
                        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
                        $usermodel = \common\models\User::findOne(['id' => $userId]);

                        if ($usermodel != NULL) { //check usrr exist
                            if ($usermodel->user_type != 3) { // check it is not a guest
                                $action = strtolower($_SERVER['REQUEST_METHOD']); // Getting action from request header
                                $data = $this->$action($headers, $post); // Call respective action with post data and headers
                                if ($data != NULL) { // check the result have value
                                    if ($data['error'] != NULL) { // Error Found on the reques
                                        $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'checkout');
                                        \Yii::$app->response->data = $array;
                                    } else if ($data['data'] != NULL) { //success
                                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'checkout');
                                    } else { // NO data Found based  on request
                                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'checkout');
                                    }
                                } else { // NO data Found based  on request
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'checkout');
                                }
                            } else { //Un autherised Auth Token
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'checkout');
                            }
                        } else { //Un autherised Auth Token
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'checkout');
                        }
                    } else { //Un autherised Auth Token
                        \Yii::$app->response->statusCode = 401;
                        $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'checkout');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Auth token not provided
                    \Yii::$app->response->statusCode = 401;
                    $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'checkout');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'checkout');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'checkout');
        }
    }

    public function post($headers, $post = []) { // post operation for creating checkout
        $name = "Checkout ";
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
        $params = ['order_id', 'payment_status'];
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
                $models = \common\models\User::findOne($userId);
                //setting model Attributes
                if ($models != NULL) {

                    $transaction = Yii::$app->db->beginTransaction();
                    $model = \common\models\Orders::find()->where(['id' => $post['order_id'], 'user_id' => $userId])->one();
                    if ($model != NULL) {
                        if (isset($post['transaction_id'])) {
                            $model->transaction_id = $post['transaction_id'];
                        }
                        $model->status = 2;
                        $model->payment_status = $post['payment_status'];  // 0-Pending,1-Success, 2- Failed
                        $model->amount_paid = $model->total_amount;  // 0-Pending,1-Success, 2- Failed
                        if ($model->save()) {
                            $orderproductmodel = \common\models\OrderProducts::find()->where(['order_id' => $post['order_id'], 'user_id' => $userId])->all();
                            if ($orderproductmodel != NULL) {
                                foreach ($orderproductmodel as $orderproduct) {
                                    $orderproduct->status = 2;
                                    if ($orderproduct->save()) {
                                        $order_history = new \common\models\OrderHistory();
                                        $order_history->order_id = $model->id;
                                        $order_history->order_product_id = $orderproduct->id;
                                        $order_history->order_status = 2;
                                        $order_history->status = 1;
                                        $order_history->created_by = $orderproduct->user_id;
                                        $order_history->updated_by = $orderproduct->user_id;
                                        $order_history->updated_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                                        $order_history->created_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                                        if ($order_history->save()) {

                                        } else {
                                            $transaction->rollBack();
                                            $errors[] = $order_history->errors;
                                        }
                                    } else {
                                        $transaction->rollBack();
                                        $errors[] = $order_history->errors;
                                    }
                                }
                            }

                            if ($errors != NULL) {
                                $transaction->rollBack();
                            } else {
                                $order_payment = new \common\models\OrderPayments();
                                $order_payment->order_id = $model->id;
                                $order_payment->pay_amount = $model->total_amount;
                                $order_payment->pay_type = $model->payment_method;
                                $order_payment->transaction_id = $post['transaction_id'];
                                $order_payment->comment = "";
                                $order_payment->payment_status = 1;
                                $order_payment->status = 1;
                                $order_payment->created_by = $model->user_id;
                                $order_payment->updated_by = $model->user_id;
                                $order_payment->updated_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                                $order_payment->created_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                                if ($order_payment->save()) {
                                    if (Yii::$app->ManageRequest->getVariable('environment') == "S" || Yii::$app->ManageRequest->getVariable('environment') == "P") { // Project is on staging or in Prouction
                                        Yii::$app->MailRequest->sendUserOrderConfirmation($model, $lang); // Sending Account create confirmation mail to user
                                    }
                                } else {
                                    $transaction->rollBack();
                                    $errors[] = $order_payment->errors;
                                }
                                $payment_method_name = "Card";
                                if ($model->payment_method == 1) {
                                    $payment_method_name = Yii::$app->ManageRequest->getMessage('card', $lang);
                                } else if ($model->payment_method == 2) {
                                    $payment_method_name = Yii::$app->ManageRequest->getMessage('cash-on-delivery', $lang);
                                } else if ($model->payment_method == 3) {
                                    $payment_method_name = Yii::$app->ManageRequest->getMessage('card-on-delivery', $lang);
                                }
                                $getOrder = \common\models\Orders::findOne(['id' => $model->id]);
                                $return = [//return files
                                    "order_id" => $model->id,
                                    "order_status" => $model->status,
                                    "order_status_name" => $model->orderStatus->name,
                                    "total_amount" => number_format((float) round($model->total_amount, 2), 2, '.', ''),
                                    "shipping_charge" => number_format((float) round($model->shipping_charge, 2), 2, '.', ''),
                                    "payment_method" => $model->payment_method,
                                    "payment_method_name" => $payment_method_name,
                                    "order_date" => $getOrder != NULL ? date('d/m/Y', strtotime($getOrder->created_at)) : "",
                                ];
                                $transaction->commit();
                                if ($model->payment_status == 1) {
                                    $template_key["{%order_id%}"] = $model->id;
                                    $titleEn = $this->getMessage("order_confirm_title", 1);
                                    $titleAr = $this->getMessage("order_confirm_title", 2);
                                    $bodyEn = $this->getBody("order_confirm_description", $template_key, 1);
                                    $bodyAr = $this->getBody("order_confirm_description", $template_key, 2);
                                    $notif_key['type'] = 2;
                                    $notif_key['redirection'] = "ORDER_CONFIRMED";
                                    $data = [
                                        "title" => [
                                            "en" => $titleEn,
                                            "ar" => $titleAr
                                        ],
                                        "description" => [
                                            "en" => $bodyEn,
                                            "ar" => $bodyAr
                                        ],
                                        "reciever_type" => 1,
                                        "redirection_id" => NULL,
                                        "notification_type" => 2,
                                        "notif_key" => $notif_key,
                                        "marketing_image" => "",
                                        "reciever" => [$model->user_id],
                                    ];
                                    $saveNotifications = Yii::$app->NotificationManager->savenotifications($data);
                                    $result = Yii::$app->NotificationManager->pushnotification($model->user_id, $titleEn, $titleAr, $bodyEn, $bodyAr, $notif_key);
                                }
                            }
                        } else {
                            $errors[] = $order_history->errors;
                        }
                    }
                }
            }
        } else {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(449, $name, $lang, $post, $post, 'checkout');
            Yii::$app->end();
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

    function getMessage($message, $lang) {
        $get_message = \common\models\MobileStrings::find()->where([
                    'string_key' => $message])->one();

        if ($get_message != NULL) {

            if ($lang == 2) {

                return $get_message->string_ar;
            } else {
                return$get_message->string_en;
            }
        } else {
            return "";
        }
    }

    function getBody($desc_key, $template_key = [], $lang) {

        $body = $this->getMessage($desc_key, $lang);
        if ($template_key != NULL) {

            foreach ($template_key as $key => $val) {
                $body = str_replace($key, $val, $body);
            }
        } return $body;
    }

}
