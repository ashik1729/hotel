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
class FeaturesController extends Controller {

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
        $action_list = ['POST'];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $name = "Login";
        $headers = Yii::$app->request->headers;
        $model = new \common\models\User();
        $model->scenario = 'login_user';
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
                                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'features');
                                    \Yii::$app->response->data = $array;
                                } else if ($data['data'] != NULL) {
                                    $transaction->commit();
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'features');
                                } else {
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'features');
                                }
                            } else {
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'features');
                            }
                        } else {

                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(443, $name, $lang, $post, (object) [], 'features');
                        }
                    } else {
                        \Yii::$app->response->statusCode = 401;

                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'features');
                    }
                } else {
                    \Yii::$app->response->statusCode = 401;

                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'features');
                }
            } else {

                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'features');
            }
        } else {

            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'features');
        }
    }

    public function post($headers, $post = []) {

        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $finaldata = [];
        $return = [];
        $return['merchant'] = [];
        $return['features'] = [];
        $fet_ids = [];
        $errors = [];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
        if (isset($post) && $post != NULL) {
            foreach ($post as $key => $val) {
                if ($val == NULL || $val == "") {
                    $errors[$key] = $val;
                }
            }
            if ($errors == NULL) {
                if (isset($post['merchant_id']) && $post['merchant_id'] != NULL) {
                    $ids = $post['merchant_id'];
                    $fet_list = [];

                    foreach ($post['merchant_id'] as $merchant_id) {
                        $merchant = \common\models\Merchant::findOne($merchant_id);
                        if ($merchant != NULL) {
                            $merchantcategory = [];
                            if ($merchant->category != "") {
                                $exp_category = explode(",", $merchant->category);
                                if ($exp_category != NULL) {
                                    foreach ($exp_category as $exp_cat) {
                                        $merchantcat = \common\models\MerchantCategory::findOne(['id' => $exp_cat]);
                                        if ($merchantcat != NULL) {
                                            array_push($merchantcategory, [
                                                'id' => $merchantcat->id,
                                                'category_name' => $lang == 1 ? $merchantcat->name : $merchantcat->name_ar
                                            ]);
                                        }
                                    }
                                }
                            }
//                            $return['merchant'] = ['merchant_id' => $merchant->last_name, 'merchant_name' => $merchant->first_name . ' ' . $merchant->last_name];
                            // $return['merchant_name'] = ['ashik', 'ali'];
                            array_push($return['merchant'], [
                                'merchant_id' => $merchant->id,
                                'merchant_name' => $merchant->first_name . ' ' . $merchant->last_name,
                                "store_category" => $merchantcategory,
                                "phone_number" => $merchant->mobile_number,
                                "facebook" => $merchant->facebook,
                                "instagram" => $merchant->instagram,
                                "whatsapp" => $merchant->whatsapp,
                                "latitude" => $merchant->latitude,
                                "longitude" => $merchant->longitude,
                                "availability_status" => $merchant->availability,
                                "review_status" => $merchant->productReviews != NULL ? "1" : "0",
                                "review_rating" => $merchant->rating() <= 0 ? strval(5) : $merchant->rating(),
                                "reviews" => $merchant->Reviews(),
                                    ]
                            );
//                            array_push($return, $merchant->first_name . ' ' . $merchant->last_name);
                            $fet_data = \common\models\MerchantFeatureList::find()->where(['merchant_id' => $ids, 'status' => 1])->asArray()->all();
                            $fet_ids = array_unique(array_column($fet_data, 'feature_id'));
//                            if ($model != NULL) {
//                                foreach ($model as $mode) {
//                                    array_push($fet_list, [
//                                        "feature_id" => $mode->feature_id,
//                                        "merchant_id" => $mode->merchant_id,
//                                        "key" => $mode->featureList->title,
//                                        "value_en" => $mode->value_en,
//                                        "value_ar" => $mode->value_ar
//                                    ]);
//                                }
//                                $purchase_status = \common\models\OrderProducts::find()->where(['user_id' => $userId, 'merchant_id' => $mode->merchant_id])->andWhere('status >= 2')->all();
//
//                                array_push($return1, [
//                                    "merchant_id" => $mode->merchant_id,
//                                    "merchant" => $mode->merchant->first_name . ' ' . $mode->merchant->last_name,
//                                    "features" => $fet_list,
//                                    "store_category" => $merchantcategory,
//                                    "phone_number" => $merchant->mobile_number,
//                                    "facebook" => $merchant->facebook,
//                                    "instagram" => $merchant->instagram,
//                                    "whatsapp" => $merchant->whatsapp,
//                                    "latitude" => $merchant->latitude,
//                                    "longitude" => $merchant->longitude,
//                                    "availability_status" => $merchant->availability,
//                                    "review_status" => $merchant->productReviews != NULL ? "1" : "0",
//                                    "review_rating" => $merchant->rating() <= 0 ? strval(5) : $merchant->rating(),
//                                    "reviews" => $merchant->Reviews(),
//                                    "favourite_status" => $merchant->getMyFavourite($userId) != NULL ? "1" : "0",
//                                    "purchase_status" => $purchase_status != NULL ? "1" : "0",
//                                ]);
//                            }
                        }
                    }
                    if ($fet_ids != NULL) {
                        foreach ($fet_ids as $fet_id) {
                            $get_feature_master = \common\models\FeaturesList::find()->where(['id' => $fet_id, 'status' => 1])->one();
                            if ($get_feature_master != NULL) {
                                $fet_list_data = [];
                                if ($ids != NULL) {
                                    foreach ($ids as $id) {
                                        $get_features = \common\models\MerchantFeatureList::find()->where(['merchant_id' => $id, 'feature_id' => $fet_id, 'status' => 1])->one();
                                        if ($get_features != NULL) {
                                            array_push($fet_list_data, ['value' => $lang == 1 ? $get_features->value_en : $get_features->value_ar]);
                                        } else {
                                            array_push($fet_list_data, ['value' => ""]);
                                        }
                                    }
                                }
                                array_push($fet_list, ["key" => $lang == 1 ? $get_feature_master->name_en : $get_feature_master->name_ar, 'values' => $fet_list_data]);
                            }
                        }
                    }
                    $return['features'] = $fet_list;
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;


        return $finaldata;
    }

}
