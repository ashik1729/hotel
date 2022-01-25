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
class CategoryController extends Controller {

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
        $action_list = ['GET'];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Set output format structure
        $name = "Category";
        $headers = Yii::$app->request->headers;
        $data = [];
        if (isset($headers['lang']) && $headers['lang'] != "") { //setting App language
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $json = file_get_contents('php://input'); // Getting Post Data
        $post = json_decode($json, true); // Converting into Array
        $errors = [];
        $get = $_GET;
        if (isset($headers['accessToken']) && $headers['accessToken'] != "") { // Chech the Access token is set or not
            if (Yii::$app->ManageRequest->validateAccessToken($headers['accessToken'])) { // Validate Acccess Token
                $transaction = Yii::$app->db->beginTransaction(); //Database Transaction Begin
                if (isset($headers['authToken']) && $headers['authToken'] != "") { //Check the AuthToken Is Set
                    if (Yii::$app->ManageRequest->validateAuthToken($headers['authToken']) != NULL) { // Check The authToken is Valids
                        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
                        $action = strtolower($_SERVER['REQUEST_METHOD']);
                        if (in_array(strtoupper($action), $action_list, true)) { //Check the request methord is in the permitted action
                            $data = $this->$action($headers, $post);
                            if ($data != NULL) {
                                if ($data['error'] != NULL) {
                                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'category');
                                    \Yii::$app->response->data = $array;
                                } else if ($data['data'] != NULL) {
                                    $transaction->commit();
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'category');
                                } else {
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'category');
                                }
                            } else {
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'category');
                            }
                        } else {

                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(443, $name, $lang, $post, (object) [], 'category');
                        }
                    } else {
                        \Yii::$app->response->statusCode = 401;

                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'category');
                    }
                } else {
                    \Yii::$app->response->statusCode = 401;

                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'category');
                }
            } else {

                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'category');
            }
        } else {

            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'category');
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
        $datas = \common\models\Category::find()->where(['status' => 1])->all();

        $Id = Yii::$app->request->get('id');
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url
        if (isset($Id) && $Id != "") {
            $datas = \common\models\MerchantCategory::find()->where(['status' => 1, 'id' => $Id])->all();
            if ($datas != NULL) {
                foreach ($datas as $data) {
                    $result = [
                        'id' => $data->id,
                        'title' => $lang == 1 ? $data->name : $data->name_ar,
                        'description' => $lang == 1 ? $data->description : $data->description_ar,
                        'parent' => $data->id,
                        'image' => $data->image != "" ? "uploads/merchant-category/" . $data->image : "img/no-image.jpg",
                            // 'sub_category' => $this->getSubCategory($data, $lang)
                    ];
                }
            }
        } else {
            //  $query = \common\models\Category::find()->select('*,parent AS prd,id AS pid')->where(['status' => 1])->having("prd = pid");
            $query = \common\models\MerchantCategory::find()->where(['status' => 1]);
            if (isset($limit) && $limit != "") {
                $query->limit($limit);
            }
            if (isset($offset) && $offset != "") {
                $offset = ($offset - 1) * $limit;
                $query->offset($offset);
            }
            $datas = $query->all();
            if ($datas != NULL) {
                foreach ($datas as $data) {
//                    if ($data->id == $data->parent) {
                    $result[] = [
                        'id' => $data->id,
                        'title' => $lang == 1 ? $data->name : $data->name_ar,
                        'description' => $lang == 1 ? $data->description : $data->description_ar,
                        'parent' => $data->id,
                        'image' => $data->image != "" ? "uploads/merchant-category/" . $data->image : "img/no-image.jpg",
                            //  'sub_category' => $this->getSubCategory($data, $lang)
                    ];
//                    }
                }
            }
        }

        $finaldata['error'] = $errors;
        $finaldata['data'] = $result;

        return $finaldata;
    }

    private function getSubCategory($data, $lang) {
        $datas = \common\models\Category::find()->where(['status' => 1])->andWhere(["parent" => $data->id])->all();
        $result = [];
        if ($datas != NULL) {
            foreach ($datas as $data) {
                if ($data->id != $data->parent) {
                    $result[] = [
                        'id' => $data->id,
                        'title' => $lang == 1 ? $data->category_name : $data->category_name_ar,
                        'description' => $lang == 1 ? $data->description : $data->description_ar,
                        'parent' => $data->parent,
                        'image' => $data->image != "" ? "uploads/category/" . $data->id . "/image/" . $data->image : "img/no-image.jpg",
                        'sub_category' => $this->getSubCategory($data, $lang)
                    ];
                }
            }
        }
        return $result;
    }

}
