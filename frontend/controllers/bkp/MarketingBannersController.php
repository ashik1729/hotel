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
class MarketingBannersController extends Controller {

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

        header('Content-type:appalication/json');
        $action_list = ['GET', "POST"];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $name = "Marketing Banner";
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
                                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'marketing');
                                    \Yii::$app->response->data = $array;
                                } else if ($data['data'] != NULL) {
                                    $transaction->commit();
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'marketing');
                                } else {
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'marketing');
                                }
                            } else {
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'marketing');
                            }
                        } else {

                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(443, $name, $lang, $post, (object) [], 'marketing');
                        }
                    } else {
                        \Yii::$app->response->statusCode = 401;

                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'marketing');
                    }
                } else {
                    \Yii::$app->response->statusCode = 401;

                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'marketing');
                }
            } else {

                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'marketing');
            }
        } else {

            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'marketing');
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
        $result = [];
        $Id = Yii::$app->request->get('id');
        if (isset($Id) && $Id != "") {

            $mode = \common\models\Banner::find()->where(['id' => $Id, 'status' => 1])->one();
            if ($mode != NULL) {

                $return = [
                    "id" => $mode->id,
                    "name" => $mode->name,
                    "description" => $lang == 1 ? $mode->description_en : $mode->description_ar,
                    'image_android' => $mode->file_and != "" ? "uploads/marketing_banners/" . $mode->id . "/android/" . $mode->file_and : "img/no-image.jpg",
                    'image_ios' => $mode->file_ios != "" ? "uploads/marketing_banners/" . $mode->id . "/ios/" . $mode->file_ios : "img/no-image.jpg",
                    "map_type" => $mode->map_type,
                    "map_type_name" => $mode->map_type == 0 ? "No Mapping" : ($mode->map_type == 1 ? "Products" : ($mode->map_type == 2 ? "Category" : "Other")),
                    "map_to" => $mode->map_to,
                ];
            }
        }

        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;

        return $finaldata;
    }

    public function post($headers, $post = []) {

        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"'));

        $finaldata = [];
        $return = [];
        $errors = [];
        $params = ['store_id', 'device_type'];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
        if (isset($post) && $post != NULL) {
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

            if ($errors == NULL) {

                $model = \common\models\Banner::find()->where(['store' => $post['store_id'], 'status' => 1])->all();
                if ($model != NULL) {
                    foreach ($model as $mode) {
                        if ($post['device_type'] == 1) {
                            array_push($return, [
                                "id" => $mode->id,
                                "name" => $mode->name,
                                "description" => $lang == 1 ? $mode->description_en : $mode->description_ar,
                                'image' => $mode->file_and != "" ? "uploads/marketing_banners/" . $mode->id . "/android/" . $mode->file_and : "img/no-image.jpg",
                                "map_type" => $mode->map_type,
                                "map_type_name" => $mode->map_type == 0 ? "No Mapping" : ($mode->map_type == 1 ? "Products" : ($mode->map_type == 2 ? "Category" : "Other")),
                                "map_to" => $mode->map_to,
                            ]);
                        } else {
                            array_push($return, [
                                "id" => $mode->id,
                                "name" => $mode->name,
                                "description" => $lang == 1 ? $mode->description_en : $mode->description_ar,
                                'image' => $mode->file_ios != "" ? "uploads/marketing_banners/" . $mode->id . "/ios/" . $mode->file_ios : "img/no-image.jpg",
                                "map_type" => $mode->map_type,
                                "map_type_name" => $mode->map_type == 0 ? "No Mapping" : ($mode->map_type == 1 ? "Products" : ($mode->map_type == 2 ? "Category" : "Other")),
                                "map_to" => $mode->map_to,
                            ]);
                        }
                    }
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;


        return $finaldata;
    }

}
