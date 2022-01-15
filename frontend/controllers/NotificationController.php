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
class NotificationController extends Controller {

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

    public function actionNotification() {

        header('Content-type:appalication/json');
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
                        $data['userId'] = $userId;
                        $action = strtolower($_SERVER['REQUEST_METHOD']);
                        $data = $this->$action($headers, $post);

                        if ($data != NULL) {
                            if ($data['error'] != NULL) {
                                $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'notification');
                                \Yii::$app->response->data = $array;
                            } else if ($data['data'] != NULL) {
                                $transaction->commit();
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'notification');
                            } else {
                                $arrayy = Yii::$app->ManageRequest->getCode(425, $name, $lang, $post, [], 'notification');
                                $array['message'] = Yii::$app->ManageRequest->getMessage('no_notification_available', $lang);
                                $array['status'] = 200;
                                $array['data']['value'] = [];
                                $array['actualData'] = $data;
                                \Yii::$app->response->data = $array;
                            }
                        } else {
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'notification');
                        }
                    } else {
                        \Yii::$app->response->statusCode = 401;
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'notification');
                    }
                } else {
                    \Yii::$app->response->statusCode = 401;
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'notification');
                }
            } else {

                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'notification');
            }
        } else {

            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'notification');
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
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
        /* @var $_GET type */
        $Id = Yii::$app->request->get('id');
        $getUser = \common\models\User::findOne(['id' => $userId]);
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url

        if ($getUser != NULL) {
            if (isset($Id) && $Id != "") {
                /* Getting One Result based on given ID */
                $model = \common\models\Notification::find()->where(['id' => $Id, 'receiver_id' => $userId])->one();
                if ($model != NULL) {
                    $return = $model;
                    $return['params'] = unserialize($model->params);
                    $return['title'] = $lang == 1 ? $model->title : $model->title_ar;
                    $return['description'] = $lang == 1 ? $model->description : $model->description_ar;
                    unset($return['description_ar']);
                    unset($return['title_ar']);
                    $return['image'] = $model->image != '' ? 'uploads/marketing-notification/' . $model->image : '';
                }
            } else {
//                if ($getUser->user_type == 3) {
//                    $query = \common\models\Notification::find()->where(['receiver_id' => -1]);
//                } else {
//                }
                $query = \common\models\Notification::find()->where(['receiver_id' => $userId]);

                if (isset($limit) && $limit != "") {
                    $query->limit($limit);
                }
                if (isset($offset) && $offset != "") {
                    $offset = ($offset - 1) * $limit;
                    $query->offset($offset);
                }
                $models = $query->all();
                if ($models != NULL) {
                    foreach ($models as $model) {

                        // $result['params'] = unserialize($model->params);
                        $result['id'] = $model->id;
                        $result['title'] = $lang == 1 ? $model->type->name : $model->type->name_ar;
                        $result['description'] = $lang == 1 ? $model->description : $model->description_ar;
                        $result['receiver_id'] = $model->receiver_id;
                        $result['reciever_type'] = $model->reciever_type; //1-User,2-Merchant,3-guest
                        $result['read_status'] = $model->read_status;
                        $result['redirection'] = $model->type->id;
                        $result['redirection_name'] = $model->type->can_name;
                        $result['redirection_id'] = $model->redirection_id;
                        $result['image'] = $model->image != '' ? 'uploads/marketing-notification/' . $model->image : '';
                        $result['icon'] = $model->type->image != '' ? 'uploads/notification-type/' . $model->type->image : '';
                        $result['date'] = date('d.m.Y', strtotime($model->created_at));
                        $result['time'] = date('h:i A', strtotime($model->created_at));
                        array_push($return, $result);
                    }
                }
            }
        }
        $finaldata['error'] = [];
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
        $data = [];
        $errors = [];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
        $params = ['id'];

        if (isset($post) && $post != NULL) {
//            foreach ($post as $key => $val) {
//                if ($val == NULL || $val == "") {
//                    $errors[$key] = $val;
//                }
//            }

            if ($errors == NULL) {

                $model = new \common\models\Notification();
                $model->id = uniqid('AGOGO');

                $model->attributes = $post;
                $model->receiver_id = $userId;
                $model->params = "";
                $model->redirection_id = 2;
                $model->status = 1;
                $model->reciever_type = 1;
                $model->read_status = 0;
                if ($model->save()) {
                    $template_key["{%sample_data%}"] = $model->description;
                    $titleEn = $this->getMessage("sample_notification_title", 1);
                    $titleAr = $this->getMessage("sample_notification_title", 2);
                    $bodyEn = $this->getBody("sample_notification_description", $template_key, 1);
                    $bodyAr = $this->getBody("sample_notification_description", $template_key, 2);
                    $notif_key['product_id'] = $model->redirection_id;
                    $notif_key['type'] = 4;
                    $notif_key['redirection'] = "ORDER_RECEPTION";
                    $data = [
                        "title" => [
                            "en" => $titleEn,
                            "ar" => $titleAr
                        ],
                        "description" => [
                            "en" => $bodyEn,
                            "ar" => $bodyAr
                        ],
                        "reciever_type" => 1,
                        "redirection_id" => NULL,
                        "notification_type" => 4,
                        "notif_key" => $notif_key,
                        "marketing_image" => "",
                        "reciever" => [$userId],
                    ];
//                    print_r($data);
//                    exit;
                    $result = Yii::$app->NotificationManager->pushnotification($userId, $titleEn, $titleAr, $bodyEn, $bodyAr, $notif_key);
                } else {

                    $err = [];
                    foreach ($model->getErrors() as $error) {
                        $err[] = $error[0];
                    }
                    $errors[] = $err;
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $data;
        return $finaldata;
    }

    function getMessage($message, $lang) {
        $get_message = \common\models\MobileStrings::find()->where([
                    'string_key' => $message])->one();

        if ($get_message != NULL) {

            if ($lang == 2) {

                return $get_message->string_ar;
            } else {
                return$get_message->string_en;
            }
        } else {
            return "";
        }
    }

    function getBody($desc_key, $template_key = [], $lang) {

        $body = $this->getMessage($desc_key, $lang);
        if ($template_key != NULL) {

            foreach ($template_key as $key => $val) {
                $body = str_replace($key, $val, $body);
            }
        } return $body;
    }

    public function put($headers, $post = []) {

        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"'));
        $finaldata = [];
        $return = [];
        $errors = [];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
        $getUser = \common\models\User::findOne(['id' => $userId]);
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url
        $params = ['id'];

        if ($getUser != NULL) {

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
//                if (!isset($post['read_status']) || $post['read_status'] == "") {
//                    $errors[] = "Read Status Missing";
//                }

                if ($errors == NULL) {
                    $model = \common\models\Notification::find()->where(['id' => $post['id'], 'receiver_id' => $userId])->one();
                    if ($model != NULL) {
                        $model->read_status = $post['read_status'];
                        if ($model->save()) {
//                            if ($getUser->user_type == 3) {
//                                $query = \common\models\Notification::find()->where(['receiver_id' => -1]);
//                            } else {
//                            }
                            $query = \common\models\Notification::find()->where(['receiver_id' => $userId]);

                            if (isset($limit) && $limit != "") {
                                $query->limit($limit);
                            }
                            if (isset($offset) && $offset != "") {
                                $offset = ($offset - 1) * $limit;
                                $query->offset($offset);
                            }
                            $models = $query->all();
                            if ($models != NULL) {
                                foreach ($models as $model) {

                                    // $result['params'] = unserialize($model->params);
                                    $result['id'] = $model->id;
                                    $result['title'] = $lang == 1 ? $model->type->name : $model->type->name_ar;
                                    $result['description'] = $lang == 1 ? $model->description : $model->description_ar;
                                    $result['receiver_id'] = $model->receiver_id;
                                    $result['reciever_type'] = $model->reciever_type; //1-User,2-Merchant,3-guest
                                    $result['read_status'] = $model->read_status;
                                    $result['redirection'] = $model->type->id;
                                    $result['redirection_name'] = $model->type->can_name;
                                    $result['redirection_id'] = $model->redirection_id;
                                    $result['image'] = $model->image != '' ? 'uploads/marketing-notification/' . $model->image : '';
                                    $result['icon'] = $model->type->image != '' ? 'uploads/notification-type/' . $model->type->image : '';
                                    $result['date'] = date('d.m.Y', strtotime($model->created_at));
                                    $result['time'] = date('h:i A', strtotime($model->created_at));
                                    array_push($return, $result);
                                }
                            }
                        } else {

                            $err = [];
                            foreach ($model->getErrors() as $error) {
                                $err[] = $error[0];
                            }
                            $errors[] = $err;
                        }
                    }
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata;
    }

    public function actionNotificationType() {

        header('Content-type:appalication/json');
        $name = "Notification Type";
        $headers = Yii::$app->request->headers;
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"'));
        $result = [];




        if (isset($headers['authToken']) && $headers['authToken'] != "") {
            if (Yii::$app->ManageRequest->validateAuthToken($headers['authToken']) != NULL) {

                $datas = \common\models\NotificationType::find()->where(['status' => 1])->all();
                if ($datas != NULL) {
                    foreach ($datas as $data) {
                        $result[] = [
                            'id' => $data->id,
                            'name' => $lang == 1 ? $data->name : $data->name_ar,
                        ];
                    }
                }
                if ($result != NULL) {
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, [], $result, 'notification');
                } else {
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, [], [], 'notification');
                }
            } else {

                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], [], 'notification');
            }
        } else {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], [], 'notification');
        }
    }

}
