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
class SearchHistoryController extends Controller {

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
                                    $array['message'] = Yii::$app->ManageRequest->getMessage('not_search_history_found', $lang);
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

    public function get($headers, $post = []) { // post operation for creating reviews
//        $val = 'a:6:{i:0;a:13:{s:9:"longitude";s:1:"0";s:8:"category";s:1:"0";s:12:"availability";s:1:"0";s:14:"type_of_search";s:1:"3";s:9:"item_type";s:1:"1";s:8:"latitude";s:1:"0";s:6:"radius";s:1:"0";s:10:"search_key";s:11:"Jungle book";s:4:"city";s:1:"0";s:6:"rating";s:1:"0";s:10:"created_at";s:10:"2021-10-03";s:10:"updated_at";s:10:"2021-10-24";s:5:"count";i:142;}i:1;a:13:{s:6:"radius";s:0:"";s:9:"item_type";s:1:"1";s:12:"availability";s:0:"";s:8:"latitude";s:9:"25.286106";s:14:"type_of_search";s:1:"1";s:9:"longitude";s:9:"51.534817";s:8:"category";s:0:"";s:6:"rating";s:0:"";s:10:"search_key";s:6:"orange";s:4:"city";s:0:"";s:10:"created_at";s:10:"2021-10-03";s:10:"updated_at";s:10:"2021-10-25";s:5:"count";i:53;}i:2;a:13:{s:8:"latitude";s:0:"";s:9:"longitude";s:0:"";s:12:"availability";s:0:"";s:10:"search_key";s:7:"laptops";s:4:"city";s:0:"";s:6:"radius";s:0:"";s:8:"category";s:0:"";s:9:"item_type";s:1:"2";s:14:"type_of_search";s:1:"2";s:6:"rating";s:0:"";s:10:"created_at";s:10:"2021-10-03";s:10:"updated_at";s:10:"2021-10-03";s:5:"count";i:1;}i:3;a:13:{s:14:"type_of_search";s:1:"1";s:9:"item_type";s:1:"1";s:4:"city";s:0:"";s:8:"latitude";s:9:"25.286106";s:6:"rating";s:0:"";s:10:"search_key";s:4:"test";s:9:"longitude";s:9:"51.534817";s:6:"radius";s:0:"";s:12:"availability";s:0:"";s:8:"category";s:0:"";s:10:"created_at";s:10:"2021-10-04";s:10:"updated_at";s:10:"2021-10-04";s:5:"count";i:1;}i:4;a:13:{s:9:"item_type";s:1:"2";s:8:"latitude";s:0:"";s:9:"longitude";s:0:"";s:12:"availability";s:1:"1";s:4:"city";s:0:"";s:6:"rating";s:0:"";s:6:"radius";s:0:"";s:14:"type_of_search";s:1:"3";s:10:"search_key";s:5:"fruts";s:8:"category";s:0:"";s:10:"created_at";s:10:"2021-10-04";s:10:"updated_at";s:10:"2021-10-04";s:5:"count";i:6;}i:5;a:13:{s:8:"category";s:0:"";s:4:"city";s:0:"";s:9:"item_type";s:1:"1";s:10:"search_key";s:0:"vegitable";s:14:"type_of_search";s:1:"3";s:12:"availability";s:1:"0";s:8:"latitude";s:0:"";s:6:"radius";s:0:"";s:6:"rating";s:0:"";s:9:"longitude";s:0:"";s:10:"created_at";s:10:"2021-10-04";s:10:"updated_at";s:10:"2021-10-04";s:5:"count";i:2;}}';
//        $search_key_arrays = unserialize($val);
//        print_r($search_key_arrays);
//        exit;
        $name = "Product_service_store Detail Page";
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url
        $lang = intval(trim($lang, '"')); // Language into Integer
        $finaldata = [];
        $return = [];
        $errors = [];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        if ($errors == NULL) { // Any Error in the post data
            $store_id = \common\models\Franchise::find()->where(['access_token' => $headers['accessToken'], 'status' => 10])->one();

            $check_history_exist = \common\models\SearchHistory::find()->where(['user_id' => $userId, 'store' => $store_id->id])->one();

            if ($check_history_exist != NULL) {
//                echo '<pre/>';

                $search_key_arrays = unserialize($check_history_exist->search_key);
                $datas = [];
                if ($search_key_arrays != NULL) {
                    foreach ($search_key_arrays as $search_key_array) {
                        if (isset($search_key_array['search_key']) && $search_key_array['search_key'] != '') {
                            array_push($datas, ['search_key' => $search_key_array['search_key'], 'date' => $search_key_array['created_at']]);
                        }
                    }


                    array_multisort(array_map('strtotime', array_column($datas, 'date')), SORT_DESC, $datas);
                }

                if ($datas != NULL) {

                    $datelists = array_unique(array_column($datas, 'date'));


                    if ($datelists != NULL) {
                        foreach ($datelists as $datelist) {
                            $history = [];
                            foreach ($datas as $data) {
                                if ($data['date'] == $datelist) {
                                    array_push($history, $data['search_key']
                                    );
                                }
                            }
                            if ($history != NULL) {
                                $history = array_values(array_unique(array_filter($history)));
                            }
                            array_push($return, [
                                'date' => $datelist,
                                'search_keys' => $history,
                                    ]
                            );
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
