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
class SupportController extends Controller {

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
        $action_list = ['POST', 'GET', 'PUT'];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $name = "Suport Ticket";
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
        //  $json = file_get_contents('php://input');
        //  $post = json_decode($json, true);
        $get = $_GET;
        $post = $_POST; // Converting into Array

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
                                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'support');
                                    \Yii::$app->response->data = $array;
                                } else if ($data['data'] != NULL) {
                                    $transaction->commit();
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'support');
                                } else {
                                    $arrayy = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, [], 'support');
                                    $array['message'] = Yii::$app->ManageRequest->getMessage('no_support_conversation_found', $lang);
                                    $array['status'] = 200;
                                    $array['data']['value'] = [
                                        'id' => null,
                                        'status' => 0,
                                        'chat_list' => []
                                    ];
                                    \Yii::$app->response->data = $array;
                                    Yii::$app->end();
                                    //   \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'support');
                                }
                            } else {
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'support');
                            }
                        } else {

                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(443, $name, $lang, $post, (object) [], 'support');
                        }
                    } else {
                        \Yii::$app->response->statusCode = 401;
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'support');
                    }
                } else {
                    \Yii::$app->response->statusCode = 401;
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'support');
                }
            } else {

                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'support');
            }
        } else {

            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'support');
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
        // $store_id = \common\models\Franchise::find()->where(['access_token' => $headers['accessToken'], 'status' => 10])->one();
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);

        if (isset($Id) && $Id != "") {  // Is osset ID then result based on ID
            $query = \common\models\SupportTickets::find()->where(['product_id' => $Id, 'user_id' => $userId])->orderBy(['sort_order' => SORT_DESC]); // BUlding query to get Events based on conditions
            if (isset($limit) && $limit != "") {
                $query->limit($limit);
            }
            if (isset($offset) && $offset != "") {
                $offset = ($offset - 1) * $limit;
                $query->offset($offset);
            }
            $mode = $query->one();
            if ($mode != NULL) {

//                foreach ($model as $mode) {
                $chatslists = \common\models\SupportChat::find()->where(['ticket_id' => $mode->id])->all();
                $chats = [];
                if ($chatslists != NULL) {
                    foreach ($chatslists as $chatslist) {
                        array_push($chats, [//Creating Result Array
                            "id" => $chatslist->id,
                            "date" => date('d M Y H:i A', strtotime($chatslist->created_at)),
//                            "ticket_id" => $chatslist->ticket_id,
                            //  "user_id" => $chatslist->ticket->user_id,
                            //  "sender" => $chatslist->sender,
                            //  "reciever" => $chatslist->reciever,
                            "sender_type" => $chatslist->sender_type,
                            //  "reciever_type" => $chatslist->reciever_type,
                            "message" => $chatslist->message,
                            "file" => $chatslist->file != "" ? 'uploads/support-chats/' . $chatslist->id . '/' . $chatslist->file : "",
                        ]);
                    }
                }
                $return = [//Creating Result Array
                    "id" => $mode->id,
//                    "date" => date('d M Y H:i A', strtotime($mode->created_at)),
                    "status" => $mode->status, //1-Pending,2-Open,3-Closed,4-Forwarded
//                    "order_id" => $mode->order_id,
//                    "product_id" => $mode->orderProduct->product->id,
//                    "product_name" => $lang == 1 ? $mode->orderProduct->product->product_name_en : $mode->orderProduct->product->product_name_en,
                    "chat_list" => $chats,
                ];
//                }
            }
        } else { // Result base on store ID
//            if ($params != NULL) {
//                foreach ($params as $param) {
//                    if (isset($get[$param])) {
//                        if ($get[$param] == NULL || $get[$param] == "") {  // Checking all Get Params are filled if ID not there
//                            $errors[$param] = $get[$param]; // Creating Error instence
//                        }
//                    } else {
//                        $errors[$param] = NULL;
//                    }
//                }
//            }
//            $query = \common\models\SupportTickets::find()->where(['user_id' => $userId])->orderBy(['sort_order' => SORT_DESC]); // BUlding query to get Events based on conditions
//            if (isset($limit) && $limit != "") {
//                $query->limit($limit);
//            }
//            if (isset($offset) && $offset != "") {
//                $offset = ($offset - 1) * $limit;
//                $query->offset($offset);
//            }
//            $model = $query->all();
//            if ($model != NULL) {
//                foreach ($model as $mode) {
//                    $chatslists = \common\models\SupportChat::find()->where(['ticket_id' => $mode->id])->all();
//                    $chats = [];
//                    if ($chatslists != NULL) {
//                        foreach ($chatslists as $chatslist) {
//                            array_push($chats, [//Creating Result Array
//                                "id" => $chatslist->id,
//                                "ticket_id" => $chatslist->ticket_id,
//                                "user_id" => $chatslist->ticket->user_id,
//                                "sender" => $chatslist->sender,
//                                "reciever" => $chatslist->reciever,
//                                "sender_type" => $chatslist->sender_type,
//                                "reciever_type" => $chatslist->reciever_type,
//                                "message" => $chatslist->message,
//                                "file" => $chatslist->file != "" ? 'uploads/support-chats/' . $chatslist->id . '/' . $chatslist->file : "",
//                            ]);
//                        }
//                    }
//                    array_push($return, [//Creating Result Array
//                        "id" => $mode->id,
//                        "date" => date('d M Y H:i A', strtotime($mode->created_at)),
//                        "status" => $mode->status, //1-Pending,2-Open,3-Closed,4-Forwarded
//                        "order_id" => $mode->order_id,
//                        "product_id" => $mode->orderProduct->product->id,
//                        "product_name" => $lang == 1 ? $mode->orderProduct->product->product_name_en : $mode->orderProduct->product->product_name_en,
//                        "chat_list" => $chats,
//                    ]);
//                }
//            }
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
        $finaldata = [];
        $return = [];
        $fet_ids = [];
        $errors = [];
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
        $params = ['message', 'order_id', 'id'];
        $name = "Support insert";
        if (isset($post) && $post != NULL) {
            foreach ($post as $key => $val) {
                if ($val == NULL || $val == "") {
                    $errors[$key] = $val;
                }
            }
            if ($errors == NULL) {

                $getOrderProducts = \common\models\OrderProducts::find()->where(['order_id' => $post['order_id'], 'id' => $post['id'], 'user_id' => $userId])->one();
                if ($getOrderProducts != NULL) {
                    $transaction = Yii::$app->db->beginTransaction();
                    $model = new \common\models\SupportTickets();
                    $getSupportTikets = [];
                    if (isset($post['ticket_id']) && $post['ticket_id'] != "") {
                        $getSupportTikets = \common\models\SupportTickets::findOne(['id' => $post['ticket_id']]);
                    }
                    //setting model Attributes
                    if ($getSupportTikets != NULL) {
                        $model = $getSupportTikets;
                    } else {
                        $model = new \common\models\SupportTickets();
                        $model->id = strtoupper(uniqid('DS'));
                    }
                    $model->user_id = $userId;
                    $model->status = 1;
                    $model->order_id = $post['order_id'];
//                    $model->product_id = $getOrderProducts->product_id; // Order Product ID
                    $model->product_id = $post['id']; // Order Product ID
                    $model->created_by = $userId;
                    $model->updated_by = $userId;
                    $model->created_by_type = 1;
                    $model->updated_by_type = 1;
                    $model->sort_order = 1;
                    if ($model->save()) { // Creating notification is success
                        $file = UploadedFile::getInstanceByName('file');
                        $name = md5(microtime());
                        $modelChat = new \common\models\SupportChat();
                        if ($file) {
                            $modelChat->file = $name . '.' . $file->extension;
                        }
                        $modelChat->id = strtoupper(uniqid('DC'));
                        $modelChat->ticket_id = $model->id;
                        $modelChat->sender = $userId;
                        $modelChat->reciever = $getOrderProducts->merchant_id;
                        $modelChat->sender_type = 1;
                        $modelChat->reciever_type = 3;
                        $modelChat->message = $post['message'];
                        $modelChat->created_by = $userId;
                        $modelChat->updated_by = $userId;
                        $modelChat->created_by_type = 1;
                        $modelChat->updated_by_type = 1;
                        $modelChat->read_status = 1;
                        $modelChat->status = 1;
                        if ($modelChat->save()) {
//                            print_r($errors);
//                            exit;
                            if ($file) {
                                if ($modelChat->uploadFile($modelChat->ticket_id, $file, $name)) {

                                }
                            }
                            $query = \common\models\SupportTickets::find()->where(['status' => 1, 'user_id' => $userId, 'product_id' => $post['id']])->orderBy(['sort_order' => SORT_DESC]); // BUlding query to get Events based on conditions
                            if (isset($limit) && $limit != "") {
                                $query->limit($limit);
                            }
                            if (isset($offset) && $offset != "") {
                                $offset = ($offset - 1) * $limit;
                                $query->offset($offset);
                            }
                            $mode = $query->one();
                            if ($mode != NULL) {
//                                foreach ($model as $mode) {
                                $chatslists = \common\models\SupportChat::find()->where(['ticket_id' => $mode->id])->all();
                                $chats = [];
                                if ($chatslists != NULL) {
                                    foreach ($chatslists as $chatslist) {
                                        array_push($chats, [//Creating Result Array
                                            "id" => $chatslist->id,
                                            "date" => date('d M Y H:i A', strtotime($chatslist->created_at)),
//                            "ticket_id" => $chatslist->ticket_id,
                                            //  "user_id" => $chatslist->ticket->user_id,
                                            //  "sender" => $chatslist->sender,
                                            //  "reciever" => $chatslist->reciever,
                                            "sender_type" => $chatslist->sender_type,
                                            //  "reciever_type" => $chatslist->reciever_type,
                                            "message" => $chatslist->message,
                                            "file" => $chatslist->file != "" ? 'uploads/support-chats/' . $chatslist->id . '/' . $chatslist->file : "",
                                        ]);
                                    }
                                }
                                $return = [//Creating Result Array
                                    "id" => $mode->id,
//                    "date" => date('d M Y H:i A', strtotime($mode->created_at)),
                                    "status" => $mode->status, //1-Pending,2-Open,3-Closed,4-Forwarded
//                    "order_id" => $mode->order_id,
//                    "product_id" => $mode->orderProduct->product->id,
//                    "product_name" => $lang == 1 ? $mode->orderProduct->product->product_name_en : $mode->orderProduct->product->product_name_en,
                                    "chat_list" => $chats,
                                ];
//                                }
                            }
                            $transaction->commit();
                        } else {
                            $transaction->rollBack();

                            $errors_data = $modelChat->getErrors();
                            foreach ($errors_data as $errors_dat) {
                                $errors[] = $errors_dat[0];
                            }
                        }
                    } else { // model save is error
                        $transaction->rollBack();

                        $errors_data = $model->getErrors();
                        foreach ($errors_data as $errors_dat) {
                            $errors[] = $errors_dat[0];
                        }
                    }
                } else {
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, $post, 'support');
                    Yii::$app->end();
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;


        return $finaldata;
    }

    public function put($headers, $post = []) {

        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $finaldata = [];
        $return = [];
        $fet_ids = [];
        $errors = [];
        $get = Yii::$app->request->get(); // Getting 'limit' from url
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
        $params = ['ticket_id'];
        $name = "Update Suppost insert";
        if (isset($get) && $get != NULL) {

            if ($params != NULL) {
                foreach ($params as $param) {
                    if (isset($get[$param])) {
                        if ($get[$param] == NULL || $get[$param] == "") {
                            $errors[$param] = $get[$param];
                        }
                    } else {
                        $errors[$param] = "";
                    }
                }
            }

            if ($errors == NULL) {

                $getSupportTikets = \common\models\SupportTickets::findOne(['id' => $get['ticket_id']]);
                if ($getSupportTikets != NULL) {
                    $transaction = Yii::$app->db->beginTransaction();
                    $getSupportTikets->status = 3;
                    if ($getSupportTikets->save()) { // Creating notification is success
                        $query = \common\models\SupportTickets::find()->where(['user_id' => $userId, 'id' => $get['ticket_id']])->orderBy(['sort_order' => SORT_DESC]); // BUlding query to get Events based on conditions
                        if (isset($limit) && $limit != "") {
                            $query->limit($limit);
                        }
                        if (isset($offset) && $offset != "") {
                            $offset = ($offset - 1) * $limit;
                            $query->offset($offset);
                        }
                        $mode = $query->one();
                        if ($mode != NULL) {
//                                foreach ($model as $mode) {
                            $chatslists = \common\models\SupportChat::find()->where(['ticket_id' => $mode->id])->all();
                            $chats = [];
                            if ($chatslists != NULL) {
                                foreach ($chatslists as $chatslist) {
                                    array_push($chats, [//Creating Result Array
                                        "id" => $chatslist->id,
                                        "date" => date('d M Y H:i A', strtotime($chatslist->created_at)),
//                            "ticket_id" => $chatslist->ticket_id,
                                        //  "user_id" => $chatslist->ticket->user_id,
                                        //  "sender" => $chatslist->sender,
                                        //  "reciever" => $chatslist->reciever,
                                        "sender_type" => $chatslist->sender_type,
                                        //  "reciever_type" => $chatslist->reciever_type,
                                        "message" => $chatslist->message,
                                        "file" => $chatslist->file != "" ? 'uploads/support-chats/' . $chatslist->id . '/' . $chatslist->file : "",
                                    ]);
                                }
                            }
                            $return = [//Creating Result Array
                                "id" => $mode->id,
//                    "date" => date('d M Y H:i A', strtotime($mode->created_at)),
                                "status" => $mode->status, //1-Pending,2-Open,3-Closed,4-Forwarded
//                    "order_id" => $mode->order_id,
//                    "product_id" => $mode->orderProduct->product->id,
//                    "product_name" => $lang == 1 ? $mode->orderProduct->product->product_name_en : $mode->orderProduct->product->product_name_en,
                                "chat_list" => $chats,
                            ];
//                                }
                        }
                        $transaction->commit();
                    } else { // model save is error
                        $transaction->rollBack();

                        $errors_data = $getSupportTikets->getErrors();
                        foreach ($errors_data as $errors_dat) {
                            $errors[] = $errors_dat[0];
                        }
                    }
                } else {
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, $post, 'support');
                    Yii::$app->end();
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;


        return $finaldata;
    }

}
