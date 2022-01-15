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
class PartnersController extends Controller {

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
                                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'partners');
                                    \Yii::$app->response->data = $array;
                                } else if ($data['data'] != NULL) { //success
                                    $transaction->commit();
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'partners');
                                } else { // NO data Found based  on request
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'partners');
                                }
                            } else { // NO data Found based  on request
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'partners');
                            }
//                            } else { //Un autherised Auth Token
//                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'partners');
//                            }
                        } else { //Un autherised Auth Token
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'partners');
                        }
                    } else { //Un autherised Auth Token
                        \Yii::$app->response->statusCode = 401;
                        $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'partners');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Auth token not provided
                    \Yii::$app->response->statusCode = 401;
                    $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'partners');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'partners');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'partners');
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
        $query = \common\models\Merchant::find()->where(['status' => 10]); // Check the review is approved by admin
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
            $models = $query->one();
            if ($models != NULL) {
                $cat_ids = $models->category != '' ? explode(',', $models->category) : [];
                $features_lists = \common\models\MerchantFeatureList::find()->where(['merchant_id' => $Id, 'status' => 1])->all();
                $category_list = \common\models\Category::find()->where(['id' => $cat_ids, 'status' => 1])->all();
                $fet_list = [];
                $cat_list = [];
                if ($features_lists != NULL) {
                    foreach ($features_lists as $mode) {
                        array_push($fet_list, ["feature_id" => $mode->feature_id, "key" => $mode->featureList->title, "value" => $lang == 1 ? $mode->value_en : $mode->value_ar]);
                    }
                }
                if ($category_list != NULL) {
                    foreach ($category_list as $cat) {
                        array_push($cat_list, ["category_id" => $cat->id, "category_name" => $lang == 1 ? $cat->category_name : $cat->category_name_ar]);
                    }
                }
                $return = [
                    "id" => $models->id,
                    "full_name" => $models->first_name . ' ' . $models->last_name,
                    "features" => $fet_list,
                    "business_type" => $lang == 1 ? ($models->type_of_business != "" ? (isset($models->businessType->category_name_en) ? $models->businessType->category_name_en : "") : "") : ($models->type_of_business != "" ? (isset($models->businessType->category_name_ar) ? $models->businessType->category_name_ar : "") : ""),
                    "business_type_id" => $models->type_of_business,
                    "category" => $cat_list,
                    "phone" => $models->mobile_number,
                    "facebook" => $models->facebook,
                    "instagram" => $models->instagram,
                    "whatsapp" => $models->whatsapp,
                    'image' => $models->business_logo != "" ? "uploads/merchant/" . $models->id . "/logo/" . $models->business_logo : "img/no-image.jpg",
                ];
            }
        } else { // not carrying
            $models_data = $query->all();
            if ($models_data != NULL) {
                foreach ($models_data as $models) {
                    $cat_ids = $models->category != '' ? explode(',', $models->category) : [];
                    $features_lists = \common\models\MerchantFeatureList::find()->where(['merchant_id' => $models->id, 'status' => 1])->all();
                    $category_list = \common\models\Category::find()->where(['id' => $cat_ids, 'status' => 1])->all();
                    $fet_list = [];
                    $cat_list = [];
                    if ($features_lists != NULL) {
                        foreach ($features_lists as $mode) {
                            array_push($fet_list, ["feature_id" => $mode->feature_id, "key" => $mode->featureList->title, "value" => $lang == 1 ? $mode->value_en : $mode->value_ar]);
                        }
                    }
                    if ($category_list != NULL) {
                        foreach ($category_list as $cat) {
                            array_push($cat_list, ["category_id" => $cat->id, "category_name" => $lang == 1 ? $cat->category_name : $cat->category_name_ar]);
                        }
                    }
                    $result = [
                        "id" => $models->id,
                        "full_name" => $models->first_name . ' ' . $models->last_name,
                        "features" => $fet_list,
                        "business_type" => $lang == 1 ? ($models->type_of_business != "" ? (isset($models->businessType->category_name_en) ? $models->businessType->category_name_en : "") : "") : ($models->type_of_business != "" ? (isset($models->businessType->category_name_ar) ? $models->businessType->category_name_ar : "") : ""),
                        "business_type_id" => $models->type_of_business,
                        "category" => $cat_list,
                        "phone" => $models->mobile_number,
                        "facebook" => $models->facebook,
                        "instagram" => $models->instagram,
                        "whatsapp" => $models->whatsapp,
                        'image' => $models->business_logo != "" ? "uploads/merchant/" . $models->id . "/logo/" . $models->business_logo : "img/no-image.jpg",
                    ];
                    array_push($return, $result);
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

}
