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
class ReviewsController extends Controller {

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

        $name = "Reviews";
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
                                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'reviews');
                                    \Yii::$app->response->data = $array;
                                } else if ($data['data'] != NULL) { //success
                                    $transaction->commit();
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'reviews');
                                } else { // NO data Found based  on request
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'reviews');
                                }
                            } else { // NO data Found based  on request
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'reviews');
                            }
//                            } else { //Un autherised Auth Token
//                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'reviews');
//                            }
                        } else { //Invalid User
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'reviews');
                        }
                    } else { //Un autherised Auth Token
                        \Yii::$app->response->statusCode = 401;
                        $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'authentication');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Auth token not provided
                    \Yii::$app->response->statusCode = 401;
                    $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'authentication');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'authentication');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'authentication');
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
        $review_type = Yii::$app->request->get('review_type'); //Getting review type
        $review_for_id = Yii::$app->request->get('review_for_id'); // Getting review for id
        $query = \common\models\ProductReview::find()->where(['approvel' => 1]); // Check the review is approved by admin
//Building query based on input get params
        if (isset($limit) && $limit != "") {
            $query->limit($limit);
        }
        if (isset($offset) && $offset != "") {
            $offset = ($offset - 1) * $limit;
            $query->offset($offset);
        }
        if (isset($review_for_id) && $review_for_id != "") {
            $query->andWhere(['review_for_id' => $review_for_id]);
        }
        if (isset($review_type) && $review_type != "") {
            $query->andWhere(['review_type' => $review_type]);
        }
        if (isset($Id) && $Id != "") { //url carriying ID
            $query->andWhere(['id' => $Id]);
            $models = $query->one();
            if ($models != NULL) {
                if ($models->review_type == 1 || $models->review_type == 2) {
                    $review_for_name = $models->product ? ($lang == 1 ? $models->product->product_name_en : $models->product->product_name_ar) : "";
                } else if ($models->review_type == 3) {
                    $review_for_name = $models->merchant ? ($lang == 1 ? $models->merchant->business_name : $models->merchant->business_name) : "";
                }
                $return = [
                    "id" => $models->id,
                    "user_id" => $models->user_id,
                    "username" => $models->user->first_name . ' ' . $models->user->last_name,
                    "designation" => $models->designation,
                    "review_type" => $models->review_type,
                    "review_for_id" => $models->review_for_id,
                    "rating" => $models->rating,
                    "comment" => $models->comment,
                    'review_for_name' => $review_for_name,
                    'review_item_image' => $models->review_type == 1 ? "uploads/products/" . base64_encode($models->product->sku) . "/image/small/" . $models->product->image : ($models->review_type == 2 ? "uploads/products/" . base64_encode($models->product->sku) . "/image/small/" . $get_products->image : ($models->review_type == 3 ? "uploads/merchant/" . $models->review_for_id . "/profile/" . $models->merchant->profile_image : "img/no-image.jpg")),
                    'customer_image' => $models->user->profile_image != "" ? "uploads/user/" . $models->user_id . "/" . $models->user->profile_image : "img/no-image.jpg",
                ];
            }
        } else { // not carrying
            $models_data = $query->all();
            if ($models_data != NULL) {
                foreach ($models_data as $models) {
                    if ($models->review_type == 1 || $models->review_type == 2) {
                        $review_for_name = $models->product ? ($lang == 1 ? $models->product->product_name_en : $models->product->product_name_ar) : "";
                    } else if ($models->review_type == 3) {
                        $review_for_name = $models->merchant ? ($lang == 1 ? $models->merchant->business_name : $models->merchant->business_name) : "";
                    }
                    $result = [
                        "id" => $models->id,
                        "user_id" => $models->user_id,
                        "username" => $models->user->first_name . ' ' . $models->user->last_name,
                        "designation" => $models->designation,
                        "review_type" => $models->review_type,
                        "review_for_id" => $models->review_for_id,
                        "rating" => $models->rating,
                        "comment" => $models->comment,
                        'review_for_name' => $review_for_name,
                        'review_item_image' => $models->review_type == 1 ? "uploads/products/" . base64_encode($models->product->sku) . "/image/small/" . $models->product->image : ($models->review_type == 2 ? "uploads/products/" . base64_encode($models->product->sku) . "/image/small/" . $get_products->image : ($models->review_type == 3 ? "uploads/merchant/" . $models->review_for_id . "/profile/" . $models->merchant->profile_image : "img/no-image.jpg")),
                        'customer_image' => $models->user->profile_image != "" ? "uploads/user/" . $models->user_id . "/" . $models->user->profile_image : "img/no-image.jpg",
                    ];
                    array_push($return, $result);
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

    public function post($headers, $post = []) { // post operation for creating reviews
        $name = "Post Reviews";
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
        $params = ['review_type', 'review_for_id', 'rating'];
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
            $usermodel = \common\models\User::findOne(['id' => $userId]);
            if ($usermodel->user_type == 3) { // check it is not a guest
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'reviews');
            }
            if ($errors == NULL) { // Any Error in the post data
                $eligibility = [];
                if ($post['review_type'] != "") {
                    if ($post['review_type'] == 1 || $post['review_type'] == 2) { // Review for a product or service
                        $eligibility = \common\models\OrderProducts::find()->where(['user_id' => $userId, 'product_id' => $post['review_for_id']])->andWhere('status >= 2')->all(); //Check the user is able to review that already purchase the service or products
                        $check_review_for = \common\models\ProductsServices::findOne(['id' => $post['review_for_id']]);
                    } else if ($post['review_type'] == 3) { // Review for a merchant
                        $eligibility = \common\models\OrderProducts::find()->where(['user_id' => $userId, 'merchant_id' => $post['review_for_id']])->andWhere('status >= 2')->all(); //Check the user is able to review that already purchase item from the merchant
                        $check_review_for = \common\models\Merchant::findOne(['id' => $post['review_for_id']]);
                    }
                }
                if ($check_review_for == NULL) {
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, $post, 'reviews');
                    Yii::$app->end();
                }
                if ($eligibility == NULL) { // Not exist
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(454, $name, $lang, $post, $post, 'reviews');
                    Yii::$app->end();
                }
                //Checking Any Review Exist based on the post data
                $exist = \common\models\ProductReview::find()->where(['user_id' => $userId, 'review_type' => $post['review_type'], 'review_for_id' => $post['review_for_id']])->one();
                if ($exist != NULL) { // Not exist
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(442, $name, $lang, $post, $post, 'reviews');
                    Yii::$app->end();
                }
//                $purchase_status = [];
//                if ($post['review_type'] != "") {
//                    if ($post['review_type'] == 1 || $post['review_type'] == 2) {
//                        $purchase_status = \common\models\OrderProducts::find()->where(['user_id' => $userId, 'product_id' => $post['review_for_id']])->andWhere('status >= 2')->all();
//                    } else if ($post['review_type'] == 3) {
//                        $purchase_status = \common\models\OrderProducts::find()->where(['user_id' => $userId, 'merchant_id' => $post['review_for_id']])->andWhere('status >= 2')->all();
//                    }
//                }
//                if ($purchase_status == NULL) {
//                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(454, $name, $lang, $post, $post, 'reviews');
//                    Yii::$app->end();
//                }

                $model = new \common\models\ProductReview();
                //setting model Attributes

                if ($model != NULL) {
                    $model->user_id = $userId;
                    $model->review_type = $post['review_type'];
                    $model->review_for_id = $post['review_for_id'];
//                    $model->designation = $post['designation'];
                    $model->designation = "";
                    $model->rating = $post['rating'];
                    $model->comment = $post['comment'];
                    $model->approvel = 1;
                    $model->created_by = $userId;
                    $model->updated_by = $userId;
                    if ($model->save()) { // Creating notification is success
                        $return = [
                            "id" => $model->id,
                            "user_id" => $model->user_id,
                            "review_type" => $model->review_type,
                            "review_for_id" => $model->review_for_id,
                            "rating" => $model->rating,
                            "comment" => $model->comment,
                            "designation" => $model->designation
                        ];
                    } else { // model save is error
                        $errors_data = $model->getErrors();
                        foreach ($errors_data as $errors_dat) {
                            $errors[] = $errors_dat[0];
                        }
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
