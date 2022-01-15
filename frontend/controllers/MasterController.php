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
class MasterController extends Controller {

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
//                    'actions' => ['index', 'get'],
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

    public function actionIndex() { //Getting all master Elements
        header('Content-type:appalication/json');
        $action_list = ['GET'];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $name = "Master Content";
        $headers = Yii::$app->request->headers;
        $data = [];
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"'));
        $errors = [];
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $get = $_GET;
        if (isset($headers['accessToken']) && $headers['accessToken'] != "") {
            if (Yii::$app->ManageRequest->validateAccessToken($headers['accessToken'])) {

                $transaction = Yii::$app->db->beginTransaction();
                if (isset($headers['authToken']) && $headers['authToken'] != "") {
                    if (Yii::$app->ManageRequest->validateAuthToken($headers['authToken']) != NULL) {
                        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
                        $action = strtolower($_SERVER['REQUEST_METHOD']);
                        if (in_array(strtoupper($action), $action_list, true)) {
                            $data = $this->$action($headers, $post);
                            if ($data != NULL) {
                                if ($data['error'] != NULL) {
                                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data['error'], 'events');
                                    \Yii::$app->response->data = $array;
                                } else if ($data['data'] != NULL) {
                                    $transaction->commit();
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'events');
                                } else {
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'events');
                                }
                            } else {
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'events');
                            }
                        } else {

                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(443, $name, $lang, $post, (object) [], 'events');
                        }
                    } else {
                        \Yii::$app->response->statusCode = 401;
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'events');
                    }
                } else {
                    \Yii::$app->response->statusCode = 401;
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'events');
                }
            } else {

                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'events');
            }
        } else {

            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'events');
        }
    }

    public function get($headers, $post = []) {
        $name = "Master";
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"'));
        $finaldata = [];
        $key = Yii::$app->request->get('key'); // Store ID / Franchise ID
        if (method_exists($this, $key)) {
            $finaldata = $this->$key($headers, $post, $lang);
        } else {
            $array = Yii::$app->ManageRequest->getCode(452, $name, $lang, $post, $_GET, 'events'); // Save the result to log return the output data
            \Yii::$app->response->data = $array; // Output as json format
            Yii::$app->end();
        }

        return $finaldata;
    }

    public function country($headers, $post = [], $lang) {
        $get = $_GET;
        $params = ['key'];
        $return = [];
        $errors = [];
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url
        if (isset($get['id']) && $get['id'] != "") {  // Is osset ID then result based on ID
            $query = \common\models\Country::find()->where(['id' => $get['id'], 'status' => 1]);
            $mode = $query->one();
            if ($mode != NULL) {
                $return = [
                    "id" => $mode->id, "iso" => $mode->iso, "iso3" => $mode->iso3, "numcode" => $mode->numcode, "phonecode" => $mode->phonecode, "name" => $lang == 1 ? $mode->country_name : $mode->country_name_ar
                ];
            }
        } else { // Result base on store ID
            if ($params != NULL) {
                foreach ($params as $param) {
                    if (isset($get[$param])) {
                        if ($get[$param] == NULL || $get[$param] == "") {  // Checking all Get Params are filled if ID not there
                            $errors[$param] = $get[$param]; // Creating Error instence
                        }
                    } else {
                        $errors[$param] = NULL;
                    }
                }
            }
            $query = \common\models\Country::find()->where(['status' => 1])->orderBy(['country_name' => SORT_DESC]); // BUlding query to get Events based on conditions
            if (isset($get['limit']) && $get['limit'] != "") {
                $query->limit($get['limit']);
            }
            if (isset($get['offset']) && $get['offset'] != "") {
                $offset = ($offset - 1) * $limit;

                $query->offset($offset);
            }
            $model = $query->all();
            if ($model != NULL) {
                foreach ($model as $mode) {
                    array_push($return, [
                        "id" => $mode->id, "iso" => $mode->iso, "iso3" => $mode->iso3, "numcode" => $mode->numcode, "phonecode" => $mode->phonecode, "name" => $lang == 1 ? $mode->country_name : $mode->country_name_ar
                    ]);
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata;
    }

    public function state($headers, $post = [], $lang) {
        $get = $_GET;
        $params = ['key', 'country_id'];
        $return = [];
        $errors = [];
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url
        if (isset($get['id']) && $get['id'] != "") {  // Is osset ID then result based on ID
            $query = \common\models\States::find()->where(['id' => $get['id'], 'status' => 1]);
            $mode = $query->one();
            if ($mode != NULL) {
                $return = [
                    "id" => $mode->id, "country_id" => $mode->country_id, "country_name" => $lang == 1 ? $mode->country->country_name : $mode->country->country_name_ar, "name" => $lang == 1 ? $mode->state_name : ($mode->state_name_ar != "" ? $mode->state_name_ar : $mode->state_name)
                ];
            }
        } else { // Result base on State ID
            if ($params != NULL) {
                foreach ($params as $param) {
                    if (isset($get[$param])) {
                        if ($get[$param] == NULL || $get[$param] == "") {  // Checking all Get Params are filled if ID not there
                            $errors[$param] = $get[$param]; // Creating Error instence
                        }
                    } else {
                        $errors[$param] = NULL;
                    }
                }
            }
            if ($errors == NULL) {
                $query = \common\models\States::find()->where(['status' => 1, 'country_id' => $get['country_id']])->orderBy(['state_name' => SORT_DESC]); // BUlding query to get Events based on conditions
                if (isset($get['limit']) && $get['limit'] != "") {
                    $query->limit($get['limit']);
                }
                if (isset($get['offset']) && $get['offset'] != "") {
                    $offset = ($offset - 1) * $limit;

                    $query->offset($offset);
                }
                $model = $query->all();
                if ($model != NULL) {
                    foreach ($model as $mode) {
                        array_push($return, [
                            "id" => $mode->id, "country_id" => $mode->country_id, "country_name" => $lang == 1 ? $mode->country->country_name : $mode->country->country_name_ar, "name" => $lang == 1 ? $mode->state_name : ($mode->state_name_ar != "" ? $mode->state_name_ar : $mode->state_name)
                        ]);
                    }
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata;
    }

    public function city($headers, $post = [], $lang) {
        $get = $_GET;
        $params = ['key'];
        $return = [];
        $errors = [];
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url
        if (isset($get['id']) && $get['id'] != "") {  // Is osset ID then result based on ID
            $query = \common\models\City::find()->where(['id' => $get['id'], 'status' => 1]);
            $mode = $query->one();
            if ($mode != NULL) {
                $return = [
                    "id" => $mode->id, "state_id" => $mode->state, "country_id" => $mode->country, "name" => $lang == 1 ? $mode->name_en : ($mode->name_ar != "" ? $mode->name_ar : $mode->name_en)
                ];
            }
        } else { // Result base on State ID
            if ($params != NULL) {
                foreach ($params as $param) {
                    if (isset($get[$param])) {
                        if ($get[$param] == NULL || $get[$param] == "") {  // Checking all Get Params are filled if ID not there
                            $errors[$param] = $get[$param]; // Creating Error instence
                        }
                    } else {
                        $errors[$param] = NULL;
                    }
                }
            }
            if ($errors == NULL) {
                $query = \common\models\City::find()->where(['status' => 1]);
                if (isset($get['state_id']) && $get['state_id'] != "") {
                    $query->andWhere(['state' => $get['state_id']]);
                }
                if (isset($get['country_id']) && $get['country_id'] != "") {
                    $query->andWhere(['country' => $get['country_id']]);
                }
                $query->orderBy(['name_en' => SORT_DESC]); // BUlding query to get Events based on conditions
                if (isset($get['limit']) && $get['limit'] != "") {
                    $query->limit($get['limit']);
                }
                if (isset($get['offset']) && $get['offset'] != "") {
                    $offset = ($offset - 1) * $limit;
                    $query->offset($offset);
                }
                $model = $query->all();
                if ($model != NULL) {
                    foreach ($model as $mode) {
                        array_push($return, [
                            "id" => $mode->id, "state_id" => $mode->state, "country_id" => $mode->country, "name" => $lang == 1 ? $mode->name_en : ($mode->name_ar != "" ? $mode->name_ar : $mode->name_en)
                        ]);
                    }
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata;
    }

    public function area($headers, $post = [], $lang) {
        $get = $_GET;
        $params = ['key', 'city_id',];
        $return = [];
        $errors = [];
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url
        if (isset($get['id']) && $get['id'] != "") {  // Is osset ID then result based on ID
            $query = \common\models\Area::find()->where(['id' => $get['id'], 'status' => 1]);
            $mode = $query->one();
            if ($mode != NULL) {
                $return = [
                    "id" => $mode->id, "city_id" => $mode->city, "name" => $lang == 1 ? $mode->name_en : ($mode->name_ar != "" ? $mode->name_ar : $mode->name_en)
                ];
            }
        } else { // Result base on State ID
            if ($params != NULL) {
                foreach ($params as $param) {
                    if (isset($get[$param])) {
                        if ($get[$param] == NULL || $get[$param] == "") {  // Checking all Get Params are filled if ID not there
                            $errors[$param] = $get[$param]; // Creating Error instence
                        }
                    } else {
                        $errors[$param] = NULL;
                    }
                }
            }
            if ($errors == NULL) {
                $query = \common\models\Area::find()->where(['status' => 1, 'city' => $get['city_id']])->orderBy(['name_en' => SORT_DESC]); // BUlding query to get Events based on conditions
                if (isset($get['limit']) && $get['limit'] != "") {
                    $query->limit($get['limit']);
                }
                if (isset($get['offset']) && $get['offset'] != "") {
                    $offset = ($offset - 1) * $limit;

                    $query->offset($offset);
                }
                $model = $query->all();
                if ($model != NULL) {
                    foreach ($model as $mode) {
                        array_push($return, [
                            "id" => $mode->id, "city_id" => $mode->city, "name" => $lang == 1 ? $mode->name_en : ($mode->name_ar != "" ? $mode->name_ar : $mode->name_en)
                        ]);
                    }
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata;
    }

}
