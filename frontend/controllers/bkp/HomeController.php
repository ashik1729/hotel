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
class HomeController extends Controller {

    public $enableCsrfValidation = false;

    public static function allowedDomains() {
        date_default_timezone_set('Asia/Qatar');
        return [
//            '*', // star allows all domains
            'http://localhost:3000',
            'http://localhost:4200',
            'http://localhost:80',
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
                'Origin' => static::allowedDomains(),
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

    public function actionBusinessType() {

        header('Content-type:appalication/json');
        $name = "Business Category";
        $headers = Yii::$app->request->headers;
        $model = new \common\models\User();
        $model->scenario = 'login_user';
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"'));
        $errors = [];
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        if (isset($headers['accessToken']) && $headers['accessToken'] != "") {
            if (Yii::$app->ManageRequest->validateAccessToken($headers['accessToken'])) {
                $datas = \common\models\BusinessCategory::find()->where(['status' => 1])->all();
                $result = [];
                if ($datas != NULL) {
                    foreach ($datas as $data) {
                        if ($data->id == $data->parent) {
                            $result[] = [
                                'id' => $data->id,
                                'title' => $lang == 1 ? $data->category_name_en : $data->category_name_ar,
//                        'parent' => $data->parent,
//                        'sub_category' => $this->getSubCategory($data, $lang)
                            ];
                        }
                    }
                }
                if ($result != NULL) {
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $result, 'authentication');
                } else {
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $result, 'authentication');
                }
            } else {

                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'authentication');
            }
        } else {

            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'authentication');
        }
    }

    public function actionImageTypes() {

        header('Content-type:appalication/json');
        $action_list = ['GET'];

        $name = "Image Types";
        $headers = Yii::$app->request->headers;
        $model = new \common\models\User();
        $model->scenario = 'login_user';
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"'));
        $errors = [];
        $result = [];
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $action = strtoupper($_SERVER['REQUEST_METHOD']);

        if (in_array(strtoupper($action), $action_list, true)) {
            if (isset($_GET['id']) && $_GET['id'] != "") {
                $datas = \common\models\ImageType::find()->where(['status' => 1, 'id' => $_GET['id']])->all();
            } else {
                $datas = \common\models\ImageType::find()->where(['status' => 1])->all();
            }
            if ($datas != NULL) {
                foreach ($datas as $data) {
                    $result[] = [
                        'id' => $data->id,
                        'title' => $data->title,
                        'section_key' => $data->section_key,
                    ];
                }
            }

            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $result, 'home');
        } else {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(443, $name, $lang, $post, (object) [], 'home');
        }
    }

    public function actionFileAssets() {

        header('Content-type:appalication/json');
        $action_list = ['POST'];

        $name = "File Assets";
        $headers = Yii::$app->request->headers;
        $model = new \common\models\User();
        $model->scenario = 'login_user';
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"'));

        $errors = [];
        $return = [];
        $finalreturn = [];
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $action = strtoupper($_SERVER['REQUEST_METHOD']);
        if (isset($headers['accessToken']) && $headers['accessToken'] != "") {
            if (Yii::$app->ManageRequest->validateAccessToken($headers['accessToken'])) {
                if (in_array(strtoupper($action), $action_list, true)) {
                    if (isset($post['device_type']) && $post['device_type'] != '') {

                        $device_type = $post['device_type'];
                    } else {
                        $device_type = "";
                        $errors['device_type'] = $post['device_type'];
                        $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $errors, 'authentication');
                        \Yii::$app->response->data = $array;
                        Yii::$app->end();
                    }
                    if (isset($post['store_id']) && $post['store_id'] != '') {

                        $store_id = $post['store_id'];
                    } else {
                        $store_id = "";
                        $errors['store_id'] = $post['store_id'];
                        $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $errors, 'authentication');
                        \Yii::$app->response->data = $array;
                        Yii::$app->end();
                    }

                    if (isset($post['version']) && $post['version'] != "") {

                        $version = $post['version'];
                        if (isset($post['section_key']) && $post['section_key'] != NULL) {
                            $datas = \common\models\ImageAssets::find()->where(['status' => 1, 'type' => $post['section_key'], 'store_id' => $store_id, 'device_type' => $device_type])->andWhere("version > " . $version)->all();

                            $get_image_types = \common\models\ImageType::find()->where(['status' => 1, 'id' => $post['section_key']])->all();
                            $check_exist = \common\models\ImageAssets::find()->where(['status' => 1, 'type' => $post['section_key'], 'store_id' => $store_id, 'device_type' => $device_type])->max('version');
                        } else {
                            $get_image_types = \common\models\ImageType::find()->where(['status' => 1])->all();
                            $check_exist = \common\models\ImageAssets::find()->where(['status' => 1, 'device_type' => $device_type, 'store_id' => $store_id])->max('version');
                            $datas = \common\models\ImageAssets::find()->where(['status' => 1, 'device_type' => $device_type, 'store_id' => $store_id])->andWhere("version > " . $version)->orderBy(['sort_order' => SORT_ASC])->all();
                        }
                        if ($check_exist != NULL) {
                            $latest_version = $check_exist;
                        } else {
                            $latest_version = "0";
                        }
                        $finalreturn['latest_version'] = $latest_version;
                        foreach ($get_image_types as $get_image_type) {
                            $result = [];
                            if ($datas != NULL) {

                                foreach ($datas as $data) {
                                    if ($data->type == $get_image_type->id) {
                                        $result[] = [
                                            'id' => $data->id,
                                            'image' => $data->image != "" ? "uploads/filemanagement/" . base64_encode($data->id) . "/" . $data->image : "img/no-image.jpg",
                                            'title' => $data->title,
                                            'description' => $lang == 1 ? $data->description_en : $data->description_ar,
                                            'version' => $data->version
                                        ];
                                    }
                                }
                            }
                            if ($result != NULL) {
                                array_push($return, ["type_id" => $get_image_type->id, "version" => $get_image_type->version, "type_title" => $get_image_type->title, "section_key" => $get_image_type->section_key, 'images' => $result]);
                            }
                        }
                        $finalreturn['latest_version'] = $latest_version;
                        $finalreturn['assets'] = $return;
                        if ($return != NULL) {
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $finalreturn, 'home');
                        } else {
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $return, 'merchants');
                        }
                    } else {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'home');
                    }
                } else {
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(443, $name, $lang, $post, (object) [], 'home');
                }
            } else {

                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'authentication');
            }
        } else {

            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'authentication');
        }
    }

    public function actionGetData() {

        header('Content-type:appalication/json');
        header('Access-Control-Allow-Origin: *');
        $name = "File Assets";

        $datas = \common\models\ImageAssets::find()->where(['status' => 1])->all();
        foreach ($datas as $data) {
            $result[] = [
                'id' => $data->id,
                'image' => $data->image != "" ? "http://wlabdemo.com/caponcms/uploads/filemanagement/" . base64_encode($data->id) . "/" . $data->image : "img/no-image.jpg",
                'title' => $data->title,
                'description' => $data->description_en,
                'version' => $data->version
            ];
        }

        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, 1, [], $result, 'home');
    }

    private function getSubCategory($data, $lang) {
        $datas = \common\models\BusinessCategory::find()->where(['status' => 1])->andWhere(["parent" => $data->id])->all();

        if ($datas != NULL) {
            foreach ($datas as $data) {
                if ($data->id != $data->parent) {
                    $result[] = [
                        'id' => $data->id,
                        'title' => $lang == 1 ? $data->category_name_en : $data->category_name_ar,
                        'parent' => $data->parent,
                        'sub_category' => $this->getSubCategory($data, $lang)
                    ];
                }
            }
        }
        return $result;
    }

}
