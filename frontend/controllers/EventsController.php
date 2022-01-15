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
class EventsController extends Controller {

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
////            'only' => ['index'],
//            'rules' => [
//                [
//                    'actions' => ['index'],
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
//        parent::init();

        $url = filter_input(INPUT_SERVER, 'REQUEST_URI');
        if (strpos($url, '/ar') !== false) {
            Yii::$app->session['lang'] = 'ar';
        } else {
            Yii::$app->session['lang'] = 'en';
        }
    }

    public function actionIndex() {

        header('Content-type:appalication/json');
        $action_list = ['GET', "POST"];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $name = "Events Banner";
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
                                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'events');
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

        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"'));
        $finaldata = [];
        $return = [];
        $errors = [];
        $Id = Yii::$app->request->get('id');
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url

        $get = $_GET;
        $params = [];
        $store_id = \common\models\Franchise::find()->where(['access_token' => $headers['accessToken'], 'status' => 10])->one();
        if (isset($Id) && $Id != "") {  // Is osset ID then result based on ID
            $query = \common\models\Events::find()->where(['id' => $Id, 'status' => 1]);

            if (isset($store_id) && $store_id != "") {
                $query->andWhere(['store_id' => $store_id->id]);
            }
            $mode = $query->one();
            if ($mode != NULL) {
                $gallery = explode(',', $mode->gallery);
                $gall = [];
                if ($gallery != NULL) {
                    foreach ($gallery as $image) {
                        $gall[] = "uploads/events/" . $mode->id . "/gallery/" . $image;
                    }
                }
                $return = [
                    "id" => $mode->id,
                    "date" => date('d M Y', strtotime($mode->date_time)),
                    "time" => date('H:i', strtotime($mode->date_time)),
                    "title" => $lang == 1 ? $mode->title_en : $mode->title_ar,
                    "country" => isset($mode->city) ? ($lang == 1 ? $mode->country0->country_name : $mode->country0->country_name_ar) : "",
                    "city" => isset($mode->city) ? ($lang == 1 ? $mode->city0->name_en : $mode->city0->name_ar) : "",
                    "place" => $lang == 1 ? $mode->place : $mode->place_ar,
                    "description" => $lang == 1 ? $mode->description_en : $mode->description_ar,
                    'file' => $mode->file != "" ? "uploads/events/" . $mode->id . "/file/" . $mode->file : "img/no-image.jpg",
                    'gallery' => $gall,
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
            $query = \common\models\Events::find()->where(['status' => 1, 'store_id' => $store_id->id])->orderBy(['sort_order' => SORT_DESC]); // BUlding query to get Events based on conditions
            if (isset($limit) && $limit != "") {
                $query->limit($limit);
            }
            if (isset($offset) && $offset != "") {
                $offset = ($offset - 1) * $limit;
                $query->offset($offset);
            }
            $model = $query->all();
            if ($model != NULL) {
                foreach ($model as $mode) {
                    $gallery = explode(',', $mode->gallery);
                    $gall = [];
                    if ($gallery != NULL) {
                        foreach ($gallery as $image) {
                            $gall[] = "uploads/events/" . $mode->id . "/gallery/" . $image;
                        }
                    }
                    array_push($return, [//Creating Result Array
                        "id" => $mode->id,
                        "date" => date('d M Y', strtotime($mode->date_time)),
                        "time" => date('H:i', strtotime($mode->date_time)),
                        "title" => $lang == 1 ? $mode->title_en : $mode->title_ar,
                        "country" => isset($mode->city) ? ($lang == 1 ? $mode->country0->country_name : $mode->country0->country_name_ar) : "",
                        "city" => isset($mode->city) ? ($lang == 1 ? $mode->city0->name_en : $mode->city0->name_ar) : "",
                        "place" => $lang == 1 ? $mode->place : $mode->place_ar,
                        "description" => $lang == 1 ? $mode->description_en : $mode->description_ar,
                        "file" => $mode->file != "" ? "uploads/events/" . $mode->id . "/file/" . $mode->file : "img/no-image.jpg",
                        "gallery" => $gall,
                    ]);
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata;
    }

}
