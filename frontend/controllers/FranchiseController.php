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
class FranchiseController extends Controller {

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

    public function actionGetFranchise() {

        header('Content-type:appalication/json');
        $name = "Franchise Listing";
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
        $Id = Yii::$app->request->get('id');
        if (isset($Id) && $Id != "" && $Id > 0) {
            $data = \common\models\Franchise::find()->where(['status' => 10, 'id' => $Id])->one();
            if ($data != NULL) {
                $result = [
                    'id' => $data->id,
                    'name' => $lang == 1 ? $data->first_name . ' ' . $data->last_name : $data->first_name . ' ' . $data->last_name,
                    'country_id' => $data->country,
                    'country_name' => $lang == 1 ? $data->country0->country_name : $data->country0->country_name_ar
                ];
            }
        } else {
            $datas = \common\models\Franchise::find()->where(['status' => 10])->all();
            if ($datas != NULL) {
                foreach ($datas as $data) {
                    $result[] = [
                        'id' => $data->id,
                        'name' => $lang == 1 ? $data->first_name . ' ' . $data->last_name : $data->first_name . ' ' . $data->last_name,
                        'country_id' => $data->country,
                        'country_name' => $lang == 1 ? $data->country0->country_name : $data->country0->country_name_ar
                    ];
                }
            }
        }
        if ($result != NULL) {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $result, 'franchise');
        } else {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $result, 'franchise');
        }
    }

    public function actionGetAccessToken() {

        header('Content-type:appalication/json');
        $name = "Franchise Listing";
        $headers = Yii::$app->request->headers;
        $model = new \common\models\User();
        $model->scenario = 'login_user';
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $errors = [];
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        if (isset($post['id']) && $post['id'] != '') {

            $id = $post['id'];
            $check_model = \common\models\Franchise::find()->where(['status' => 10, 'id' => $id])->all();
            if ($check_model == NULL) {
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(420, $name, $lang, $post, (object) [], 'franchise');
                Yii::$app->end();
            }
        } else {
            $id = "";
            $errors['id'] = $post['id'];
        }

        if ($errors == NULL) {

            $data = \common\models\Franchise::find()->where(['status' => 10, 'id' => $id])->one();
            $result = [
                'id' => $data->id,
                'access_token' => $data->access_token,
                'country_id' => $data->country,
                'country_name' => $lang == 1 ? $data->country0->country_name : $data->country0->country_name_ar,
                'name' => $lang == 1 ? $data->first_name . ' ' . $data->last_name : $data->first_name . ' ' . $data->last_name
            ];
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $result, 'franchise');
        } else {

            $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $errors, 'franchise');

            \Yii::$app->response->data = $array;
        }
    }

}
