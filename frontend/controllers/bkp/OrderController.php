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
class OrderController extends Controller {

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
        $action_list = ['GET', "POST", 'PUT'];
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
                                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'OrderConfirm');
                                    \Yii::$app->response->data = $array;
                                } else if ($data['data'] != NULL) {
                                    $transaction->commit();
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'OrderConfirm');
                                } else {
                                    $arrayy = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, [], 'cart');
                                    $array['message'] = Yii::$app->ManageRequest->getMessage('no_order_found', $lang);
                                    $array['status'] = 200;
                                    $array['data']['value'] = [];
                                    \Yii::$app->response->data = $array;
                                }
                            } else {
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'OrderConfirm');
                            }
                        } else {

                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(443, $name, $lang, $post, (object) [], 'OrderConfirm');
                        }
                    } else {
                        \Yii::$app->response->statusCode = 401;
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'OrderConfirm');
                    }
                } else {
                    \Yii::$app->response->statusCode = 401;
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'OrderConfirm');
                }
            } else {
                \Yii::$app->response->statusCode = 401;
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'OrderConfirm');
            }
        } else {
            \Yii::$app->response->statusCode = 401;
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'OrderConfirm');
        }
    }

    public function post($headers, $post = []) { // post operation for creating checkout
        $name = "Checkout ";
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $finaldata = [];
        $eligibility = [];
        $return = [];
        $errors = [];
        $params = ['data', 'shipping_address', 'payment_method'];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        $store_id = \common\models\Franchise::find()->where(['access_token' => $headers['accessToken'], 'status' => 10])->one();

        if (isset($post) && $post != NULL) { // checking post data exist
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

            if ($errors == NULL) { // Any Error in the post data
                $models = \common\models\User::findOne($userId);
                //setting model Attributes
                $datas = $post['data'];
                if ($models != NULL) {
                    $totalShippingCharge = 0;
                    if ($datas != NULL) {
                        $merchantLists = array_column($datas, 'merchant_id');
                        $merchantLists = array_filter(array_unique($merchantLists));
                        if (count($merchantLists) > 1 && $post['payment_method'] != 1) {
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, [], 'order');
                            Yii::$app->end();
                        }
                        foreach ($datas as $data) {

                            $merchant = \common\models\Merchant::findOne(['id' => $data['merchant_id']]);

                            if ($merchant != NULL) {

                                $shippingMethod = \common\models\MerchantShipmentMethods::find()->where(['merchant_id' => $merchant->id, 'status' => 1, 'id' => $data['shipping_id']])->one();
                                if ($shippingMethod != NULL) {
                                    $totalShippingCharge += floatval($shippingMethod->price);
                                }
                            }
                        }
                    }
                    $cartIdlist = array_column($post['data'], 'cart_items');
                    $cartIds = $this->flatten($cartIdlist);
                    $cartExist = [];
                    if ($cartIds != NULL) {
                        foreach ($cartIds as $cartId) {
                            $getCart = \common\models\Cart::find()->where(['id' => $cartId])->one();
                            if ($getCart == NULL) {
                                $cartExist[] = "Cart Ref :" . $cartId . " Not Available in the records";
                            }
                        }
                    }
                    if ($cartExist != NULL) {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $cartExist, 'order');
                        Yii::$app->end();
                    }
                    $getCartslist = \common\models\Cart::find()->where(['id' => $cartIds])->all();
                    if (isset($post['coupon_code']) && $post['coupon_code'] != "") {
                        $coupondata = $this->getCoupon($post);
                        if ($coupondata['status'] && $coupondata['status'] == TRUE) {
                            $coupon = $coupondata['amount'];
                        } else {
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(458, $name, $lang, $post, [], 'order');
                            Yii::$app->end();
                        }
                    } else {
                        $coupon = 0;
                    }
                    $tax = 0;
                    $total_amount = $this->grandTotal($getCartslist, $totalShippingCharge, $coupon, $tax);
                    $transaction = Yii::$app->db->beginTransaction();
                    $model = new \common\models\Orders();
                    $model->user_id = $userId;
                    $model->store = $store_id->id;
//                    $model->shipping_method = $post['shipping_method'];
                    $model->ship_address = $post['shipping_address'];
                    $model->bill_address = $post['shipping_address'];
                    $model->payment_method = $post['payment_method'];  //1-Card/Online,2-cash
                    $model->payment_status = 0; // 0-Pending, 1-Success,2-Failed
                    $model->status = $post['payment_method'] == 1 ? 1 : 2;   // 0- Abonded/Deleted, 1- Pending, 2-ORder Placed, 3- Shipped, 4-Delivered, 5-Completed, 6- Returned , 7-Cancelled
                    $model->created_by = $userId;
                    $model->updated_by = $userId;
                    $model->updated_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    $model->created_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    $model->shipping_charge = $totalShippingCharge;
                    $model->total_amount = $total_amount;

                    if ($model->save()) { // Creating Order is success
                        $order_product = $this->orderProducts($model, $cartIds);

                        if ($order_product != NULL) {
                            $transaction->rollBack();
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $order_product, 'order');
                            Yii::$app->end();
                        }
                        $shippping = $this->orderShipping($model, $post['data']);
                        $getOrder = \common\models\Orders::findOne(['id' => $model->id]);
                        if ($shippping != NULL) {
                            $transaction->rollBack();
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $shippping, 'order');
                            Yii::$app->end();
                        }

                        if (\common\models\Cart::deleteAll(['AND', 'user_id = :user_id', ['IN', 'id', $getCartslist]], [':user_id' => $userId])) {
                            if ($post['payment_method'] != 1) {
                                if (Yii::$app->ManageRequest->getVariable('environment') == "S" || Yii::$app->ManageRequest->getVariable('environment') == "P") { // Project is on staging or in Prouction
                                    Yii::$app->MailRequest->sendUserOrderConfirmation($model, $lang); // Sending Account create confirmation mail to user
                                }
                            }

                            $payment_method_name = "Card";
                            if ($model->payment_method == 1) {
                                $payment_method_name = Yii::$app->ManageRequest->getMessage('card', $lang);
                            } else if ($model->payment_method == 2) {

                                $payment_method_name = Yii::$app->ManageRequest->getMessage('cash-on-delivery', $lang);
                            } else if ($model->payment_method == 3) {

                                $payment_method_name = Yii::$app->ManageRequest->getMessage('card-on-delivery', $lang);
                            }

                            $return = [//return files
                                "order_id" => $model->id,
                                "order_status" => $model->status,
                                "order_status_name" => $model->orderStatus->name,
                                "total_amount" => number_format((float) round($model->total_amount, 2), 2, '.', ''),
                                "shipping_charge" => number_format((float) round($model->shipping_charge, 2), 2, '.', ''),
                                "payment_method" => $model->payment_method,
                                "payment_method_name" => $payment_method_name,
                                "order_date" => $getOrder != NULL ? date('d/m/Y', strtotime($getOrder->created_at)) : "",
                            ];
                            $transaction->commit();
                            if ($model->payment_method == 2) {
                                $template_key["{%order_id%}"] = $model->id;
                                $titleEn = $this->getMessage("order_confirm_title", 1);
                                $titleAr = $this->getMessage("order_confirm_title", 2);
                                $bodyEn = $this->getBody("order_confirm_description", $template_key, 1);
                                $bodyAr = $this->getBody("order_confirm_description", $template_key, 2);
                                $notif_key['type'] = 2;
                                $notif_key['redirection'] = "ORDER_CONFIRMED";
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
                                    "notification_type" => 2,
                                    "notif_key" => $notif_key,
                                    "marketing_image" => "",
                                    "reciever" => [$model->user_id],
                                ];
                                $saveNotifications = Yii::$app->NotificationManager->savenotifications($data);
                                $result = Yii::$app->NotificationManager->pushnotification($model->user_id, $titleEn, $titleAr, $bodyEn, $bodyAr, $notif_key);
                            } else if ($model->payment_method == 3) {
                                $template_key["{%order_id%}"] = $model->id;
                                $titleEn = $this->getMessage("order_confirm_title", 1);
                                $titleAr = $this->getMessage("order_confirm_title", 2);
                                $bodyEn = $this->getBody("order_confirm_description", $template_key, 1);
                                $bodyAr = $this->getBody("order_confirm_description", $template_key, 2);
                                $notif_key['type'] = 2;
                                $notif_key['redirection'] = "ORDER_CONFIRMED";
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
                                    "notification_type" => 2,
                                    "notif_key" => $notif_key,
                                    "marketing_image" => "",
                                    "reciever" => [$model->user_id],
                                ];
                                $saveNotifications = Yii::$app->NotificationManager->savenotifications($data);
                                $result = Yii::$app->NotificationManager->pushnotification($model->user_id, $titleEn, $titleAr, $bodyEn, $bodyAr, $notif_key);
                            }
                        } else {
                            $transaction->rollBack();
                        }
                    } else { // model save is error
                        $errors_data = $model->errors;
                        foreach ($errors_data as $errors_dat) {
                            $errors[] = $errors_dat[0];
                        }
                    }
                }
            }
        } else {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(449, $name, $lang, $post, $post, 'order');
            Yii::$app->end();
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
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

    function flatten(array $array) {
        $return = array();
        array_walk_recursive($array, function($a) use (&$return) {
            $return[] = $a;
        });
        return $return;
    }

    public function getCoupon($post) { // Calculate Order Amount
        $status = FALSE;
        $couponPrice = 0;

        if (isset($post['coupon_code'])) {
            $date = date('Y-m-d');
            if (isset($post['data'])) {
                $data = $post['data'];
                $get_coupon = \common\models\Discounts::find()->where(['coupon_code' => $post['coupon_code']])->andWhere('discount_from <= "' . $date . '" AND discount_to >= "' . $date . '"')->one();
                if ($get_coupon != NULL) {
                    if ($data != NULL) {

                        $merchantList = array_unique(array_filter(array_column($data, 'merchant_id')));
                        $cartIdlist = array_column($data, 'cart_items');
                        $cartIds = $this->flatten($cartIdlist);
                        $getCartslist = \common\models\Cart::find()->where(['id' => $cartIds])->all();
                        $pids = [];
                        $getProducts = [];
                        if ($getCartslist != NULL) {
                            $pids = array_column($getCartslist, 'product_id');
                            $getProducts = \common\models\ProductsServices::find()->where(['id' => $pids, 'discount_id' => $get_coupon->id])->all();
                        }

                        $subtotal = $this->subTotal($getCartslist);
                        if ($get_coupon->item_type == 1) {
                            if (in_array($get_coupon->merchant_id, $merchantList)) {
                                $status = TRUE;
                                if ($get_coupon->discount_type == 1) { //Flat Reduce
                                    $couponPrice = $get_coupon->discount_rate;
                                } else { //percentage
                                    $couponPrice = $subtotal * $get_coupon->discount_rate / 100;
                                }
                            }
                        } else {
                            if ($getProducts != NULL) {
                                $status = TRUE;

                                if ($get_coupon->discount_type == 1) { //Flat Reduce
                                    $couponPrice = $get_coupon->discount_rate;
                                } else { //percentage
                                    $couponPrice = $subtotal * $get_coupon->discount_rate / 100;
                                }
                            }
                        }
                    }
                }
            }
        }
        $result['status'] = $status;
        $result['amount'] = $couponPrice;
        return $result;
    }

    public function subTotal($carts) { // Calculate Order Amount
        $total_amount = 0;
        if ($carts != NULL) {
            foreach ($carts as $carts) {
                $cart = \common\models\Cart::findOne(['id' => $carts]);
                if ($cart != NULL) {
                    $total_amount += ($cart->quantity * Yii::$app->Products->Price($cart->product));
                }
            }
        }

        return floatval($total_amount);
    }

    public function grandTotal($carts, $shipping_charge, $coupon, $tax = 0) { // Calculate Order Amount
        $subtotal_amount = 0;
        if ($carts != NULL) {
            foreach ($carts as $carts) {
                $cart = \common\models\Cart::findOne(['id' => $carts]);
                if ($cart != NULL) {
                    $subtotal_amount += ($cart->quantity * Yii::$app->Products->price($cart->product));
                }
            }
        }

        $granttotal_amount = ($subtotal_amount + $shipping_charge + $tax) - $coupon;

        return $granttotal_amount > 0 ? round($granttotal_amount, 2) : floatval(0);
    }

    public function put($headers, $post = []) { // post operation for creating reviews
        $name = "Create Cart";
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $finaldata = [];
        $return = [];
        $errors = [];
        $params = ['confirm_id', 'order_id'];
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url
//product_id - Product ID
//Status - Status may (1- add to favorite,0-Remove From Favorite)
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        if ($userId != "") {
            if (isset($post) && $post != NULL) { // checking post data exist
                if ($params != NULL) {
                    foreach ($params as $param) {
                        if (isset($post[$param])) {
                            if ($post[$param] == NULL || $post[$param] == "") {
                                $errors[$param] = $post[$param];
                            }
                        } else {
                            $errors[$param] = "";
                        }
                    }
                }
                if ($errors == NULL) {
                    $transaction = Yii::$app->db->beginTransaction();
// Any Error in the
                    $model = new \common\models\OrderHistory();

                    $check_order = \common\models\OrderProducts::find()->where(['id' => $post['confirm_id'], 'order_id' => $post['order_id']])->one();
                    if ($check_order != NULL) {
                        $check_eligible = \common\models\OrderHistory::find()->where(['order_product_id' => $check_order->id, 'order_status' => 7])->one();
                        if ($check_eligible != NULL) {
                            $check_order->status = 8;
                            $model->order_id = $post['order_id'];
                            $model->order_product_id = $check_order->id;
                            $model->status = 1;   // 0- Abonded/Deleted, 1- Pending, 2-ORder Placed, 3- Shipped, 4-Delivered, 5- Returned , 6-Cancelled
                            $model->order_status = 8;   // 0- Abonded/Deleted, 1- Pending, 2-ORder Placed, 3- Shipped, 4-Delivered, 5- Returned , 6-Cancelled
                            $model->created_by = $userId;
                            $model->updated_by = $userId;
                            $model->updated_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                            $model->created_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                            if ($model->save()) {
                                if ($check_order->save()) {
                                    $transaction->commit();
                                    $query = \common\models\Orders::find()->where(['user_id' => $userId])->andWhere("status != 0"); // Building Order Query
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
                                            $query = \common\models\OrderProducts::find()->where(['order_id' => $mode->id]); // Check the review is approved by admin
                                            $models_data = $query->all();
                                            if ($models_data != NULL) {
                                                foreach ($models_data as $model) {
                                                    $data = [
                                                        "id" => $model->id,
                                                        "date" => date('d.m.Y H:i A', strtotime($model->order->created_at)),
                                                        "order_id" => $mode->id,
                                                        "product_id" => $model->product_id,
                                                        "product_name" => $model->product ? ($lang == 1 ? $model->product->product_name_en : $model->product->product_name_ar) : "NA",
                                                        "description" => $model->product ? ($lang == 1 ? $model->product->short_description_en : $model->product->short_description_ar) : "NA",
//                        "merchant_id" => $model->merchant_id,
                                                        "quantity" => $model->quantity,
                                                        "total_amount" => Yii::$app->Currency->convert($model->amount, $mode->store, $lang),
                                                        "order_status" => $model->status,
                                                        "order_status_name" => $model->status != 0 ? ($lang == 1 ? ($model->status ? $model->orderStatus->name : $model->orderStatus->name_ar) : "NA") : "NA",
                                                        "options" => $this->getOptions($model, $lang),
                                                        "delivery_date" => $model->date != "" ? $model->date : "",
                                                        "delivery_time" => $model->booking_slot != "" ? $model->booking_slot : "",
//                                                    "order_history" => $this->getOrderHistory($model, $lang),
//  "invoice" => $this->getInvoice($model->order_id, $model->product->merchant_id),
                                                        "image" => $model->product->image != "" ? "uploads/products/" . base64_encode($model->product->sku) . "/image/" . $model->product->image : "img/no-image.jpg",
                                                    ];
                                                    array_push($return, $data);
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $transaction->rollBack();

                                    $errors_data = $check_order->getErrors();
                                    foreach ($errors_data as $errors_dat) {
                                        $errors[] = $errors_dat[0];
                                    }
                                }
                            } else {
                                $transaction->rollBack();

                                $errors_data = $model->getErrors();
                                foreach ($errors_data as $errors_dat) {
                                    $errors[] = $errors_dat[0];
                                }
                            }
                        } else {
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(457, $name, $lang, $post, $post, 'OrderConfirm');
                            Yii::$app->end();
                        }
                    } else {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(456, $name, $lang, $post, $post, 'OrderConfirm');
                        Yii::$app->end();
                    }
                }
            } else {
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(449, $name, $lang, $post, $post, 'OrderConfirm');
                Yii::$app->end();
            }
        } else {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, $post, 'reviews');
            Yii::$app->end();
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
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
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
        if (isset($Id) && $Id != "") {  // Is osset ID then result based on ID
            $query = \common\models\Orders::find()->where(['id' => $Id])->andWhere("status != 0");

            if (isset($store_id) && $store_id != "") {
                $query->andWhere(['store' => $store_id->id]);
            }
            $mode = $query->one();
            if ($mode != NULL) {

                $return = [//Creating Result Array
                    "id" => $mode->id,
                    "date" => date('d M Y H:i   ', strtotime($mode->created_at)),
                    "total_amount" => Yii::$app->Currency->convert($mode->total_amount, $mode->store, $lang),
//                    "amount_paid" => Yii::$app->Currency->convert($mode->amount_paid, $mode->store),
                    "ship_address" => $this->getAddress($mode->ship_address, $lang),
                    "bill_address" => $this->getAddress($mode->bill_address, $lang),
                    "shipping_method" => $mode->shipping_method == 1 ? Yii::$app->ManageRequest->getMessage('home_delivery', $lang) : ($mode->shipping_method == 2 ? Yii::$app->ManageRequest->getMessage('pickup_from_store', $lang) : "NA"),
                    "shipping_charge" => $mode->shipping_charge,
                    "payment_method" => $mode->payment_method == 1 ? Yii::$app->ManageRequest->getMessage('cod', $lang) : ($mode->payment_method == 2 ? Yii::$app->ManageRequest->getMessage('card', $lang) : ($mode->payment_method == 3 ? Yii::$app->ManageRequest->getMessage('online', $lang) : "NA")),
                    "payment_status" => $mode->payment_status == 0 ? Yii::$app->ManageRequest->getMessage('Pending', $lang) : ($mode->payment_method == 1 ? Yii::$app->ManageRequest->getMessage('Success', $lang) : ($mode->payment_method == 2 ? Yii::$app->ManageRequest->getMessage('Failed', $lang) : "NA")),
                    "transaction_id" => $mode->transaction_id,
                    "order_products" => $this->getOrderProducts($mode->id, $lang),
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
            $query = \common\models\Orders::find()->where(['user_id' => $userId])->andWhere("status != 0"); // Building Order Query
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
//$order = $this->getOrderProducts($mode->id, $lang);
//array_push($return, $order);
//                    array_push($return, [//Creating Result Array
//                        "id" => $mode->id,
//                        "date" => date('d M Y H:i   ', strtotime($mode->created_at)),
//                        "total_amount" => Yii::$app->Currency->convert($mode->total_amount, $mode->store),
////                        "amount_paid" => Yii::$app->Currency->convert($mode->amount_paid, $mode->store),
//                        "ship_address" => $this->getAddress($mode->ship_address, $lang),
//                        "bill_address" => $this->getAddress($mode->bill_address, $lang),
//                        "shipping_method" => $mode->shipping_method == 1 ? Yii::$app->ManageRequest->getMessage('home_delivery', $lang) : ($mode->shipping_method == 2 ? Yii::$app->ManageRequest->getMessage('pickup_from_store', $lang) : "NA"),
//                        "shipping_charge" => $mode->shipping_charge,
//                        "payment_method" => $mode->payment_method == 1 ? Yii::$app->ManageRequest->getMessage('cod', $lang) : ($mode->payment_method == 2 ? Yii::$app->ManageRequest->getMessage('card', $lang) : ($mode->payment_method == 3 ? Yii::$app->ManageRequest->getMessage('online', $lang) : "NA")),
//                        "payment_status" => $mode->payment_status == 0 ? Yii::$app->ManageRequest->getMessage('Pending', $lang) : ($mode->payment_method == 1 ? Yii::$app->ManageRequest->getMessage('Success', $lang) : ($mode->payment_method == 2 ? Yii::$app->ManageRequest->getMessage('Failed', $lang) : "NA")),
//                        "transaction_id" => $mode->transaction_id,
//                        "order_products" => $this->getOrderProducts($mode->id, $lang),
//                    ]);


                    $query = \common\models\OrderProducts::find()->where(['order_id' => $mode->id]); // Check the review is approved by admin
                    $models_data = $query->all();
                    if ($models_data != NULL) {
                        foreach ($models_data as $model) {
                            $check_complaint = \common\models\SupportTickets::find()->where(['product_id' => $model->id, 'user_id' => $userId, 'order_id' => $model->order_id])->andWhere('status != 3')->one();
                            if ($check_complaint != NULL) {
                                $complaint_status = TRUE;
                            } else {
                                $complaint_status = FALSE;
                            }
                            $data = [
                                "id" => $model->id,
                                "date" => date('d.m.Y H:i A', strtotime($model->order->created_at)),
                                "order_id" => $model->order_id,
                                "complaint_status" => $complaint_status,
                                "product_id" => $model->product_id,
                                "product_name" => $model->product ? ($lang == 1 ? $model->product->product_name_en : $model->product->product_name_ar) : "NA",
                                "description" => $model->product ? ($lang == 1 ? $model->product->short_description_en : $model->product->short_description_ar) : "NA",
//                        "merchant_id" => $model->merchant_id,
                                "quantity" => $model->quantity,
                                "total_amount" => Yii::$app->Currency->convert($model->amount, $mode->store, $lang),
                                "order_status" => $model->status,
                                "order_status_name" => $model->status != 0 ? ($lang == 1 ? ($model->status ? $model->orderStatus->name : $model->orderStatus->name_ar) : "NA") : "NA",
                                "options" => $this->getOptions($model, $lang),
                                "delivery_date" => $model->date != "" ? $model->date : "",
                                "delivery_time" => $model->booking_slot != "" ? $model->booking_slot : "",
                                //"delivery_date" => $model->date,
//"delivery_time" => $model->booking_slot,
//"order_history" => $this->getOrderHistory($model, $lang),
//  "invoice" => $this->getInvoice($model->order_id, $model->product->merchant_id),
                                "image" => $model->product->image != "" ? "uploads/products/" . base64_encode($model->product->sku) . "/image/" . $model->product->image : "img/no-image.jpg",
                            ];
                            array_push($return, $data);
                        }
                    }
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata;
    }

    private function getAddress($id, $lang) {
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset');

        $query = \common\models\UserAddress::find()->where(['id' => $id]); // Check the review is approved by admin
        if (isset($limit) && $limit != "") {
            $query->limit($limit);
        }
        if (isset($offset) && $offset != "") {
            $offset = ($offset - 1) * $limit;
            $query->offset($offset);
        }
        $model = $query->one();
        $address_array = [];
        if ($model != NULL) {
            $address_array = [
                "id" => $model->id,
                "first_name" => $model->first_name,
                "last_name" => $model->last_name,
                "country_id" => $model->country,
                "country_name" => $lang == 1 ? $model->country0->country_name : ($model->country0->country_name_ar != "" ? $model->country0->country_name_ar : $model->country0->country_name),
                "state_id" => $model->state,
                "state_name" => $model->state != "" ? ($lang == 1 ? $model->state0->state_name : ($model->state0->state_name_ar != "" ? $model->state0->state_name_ar : $model->state0->state_name)) : "",
                "city_id" => $model->city,
                "city_name" => $lang == 1 ? $model->city0->name_en : ($model->city0->name_ar != "" ? $model->city0->name_ar : $model->city0->name_en),
                "streat_address" => $model->streat_address,
                "postcode" => $model->postcode,
                "phone_number" => $model->phone_number,
                "default_billing_address" => $model->default_billing_address,
                "default_shipping_address" => $model->default_shipping_address,
                "email" => $model->email,
            ];
        }
        return $address_array;
    }

//    {
//    "id":7,
//    "order_id":3,
//    "product_id":1,
//    "product_name":"Laptop",
//    "description":"Laptop",
//    "total_amount":"120 QAR",
//    "qty":"2",
//    "order_status":1,
//    "order_status_name":"Order Placed",
//    "options":[{
//        "option_type":"Color",
//        "option_value":"red"
//    },{
//        "option_type":"Size",
//        "option_value":"XXL"
//    }],
//    "image":"IMAGE_URL"
//
//}
    private function getOrderProducts($id, $lang) {
        $result_array = [];
        $order = \common\models\Orders::findOne(['id' => $id]);
        if ($order != NULL) {
            $query = \common\models\OrderProducts::find()->where(['order_id' => $id]); // Check the review is approved by admin
            $models_data = $query->all();
            if ($models_data != NULL) {
                foreach ($models_data as $model) {
                    $data = [
                        "id" => $model->id,
                        "date" => date('d.m.Y H:i A', strtotime($model->order->created_at)),
                        "order_id" => $model->id,
                        "product_id" => $model->product_id,
                        "product_name" => $model->product ? ($lang == 1 ? $model->product->product_name_en : $model->product->product_name_ar) : "NA",
                        "description" => $model->product ? ($lang == 1 ? $model->product->short_description_en : $model->product->short_description_ar) : "NA",
//                        "merchant_id" => $model->merchant_id,
                        "quantity" => $model->quantity,
                        "total_amount" => Yii::$app->Currency->convert($model->amount, $order->store, $lang),
                        "order_status" => $model->status,
                        "order_status_name" => $model->status != 0 ? ($lang == 1 ? ($model->status ? $model->orderStatus->name : $model->orderStatus->name_ar) : "NA") : "NA",
                        "options" => $this->getOptions($model, $lang),
                        //"delivery_date" => $model->date,
//"delivery_time" => $model->booking_slot,
//"order_history" => $this->getOrderHistory($model, $lang),
//  "invoice" => $this->getInvoice($model->order_id, $model->product->merchant_id),
                        "image" => $model->product->image != "" ? "uploads/products/" . base64_encode($model->product->sku) . "/image/" . $model->product->image : "img/no-image.jpg",
                    ];
                    array_push($result_array, $data);
                }
            }
        }
        return $result_array;
    }

    private function getOptions($model, $lang) {
        $return_result = [];
        if ($model != "") {
            $get_options = explode(',', $model->options);
            if ($get_options != NULL) {
                foreach ($get_options as $get_option) {
                    $option_details = $model->getAttr($get_option);
                    if ($option_details != NULL) {
                        array_push($return_result, [
                            'option_type' => $lang == 1 ? $option_details->attributesValue->attributes0->name : $option_details->attributesValue->attributes0->name_ar,
                            'option_value' => $option_details->attributesValue->value,
                        ]);
                    }
                }
            }
        }
        return $return_result;
    }

    private function getOrderHistory($model, $lang) {
        $return_result = [];
        if ($model != NULL) {
            $get_historys = \common\models\OrderHistory::find()->where(['order_product_id' => $model->id, 'status' => 1])->all();
            if ($get_historys != NULL) {
                foreach ($get_historys as $get_history) {
                    array_push($return_result, [
                        'id' => $get_history->id,
                        'order_status' => $get_history->order_status != 0 ? ($lang == 1 ? ($get_history->order_status ? $get_history->orderStatus->name : $model->orderStatus->name_ar) : "NA") : "NA",
                        'custom_comment' => $get_history->order_status_custome_comment,
                        'date' => date("Y-m-d H:i:d", strtotime($get_history->created_at))
                    ]);
                }
            }
        }
        return $return_result;
    }

    private function getInvoice($order_id, $merchant_id) {
        $return_result = [];
        if ($order_id != "" && $merchant_id != "") {
            $get_data = \common\models\OrderInvoice::findOne(['order_id' => $order_id, 'merchant_id' => $merchant_id]);
            if ($get_data != NULL) {
                array_push($return_result, [
                    'invoice' => $get_data->invoice,
                    'invoice_date' => $get_data->invoice_date,
                    'invoice_file' => "uploads/temp_invoice/" . $get_data->invoice_file,
                ]);
            }
        }
        return $return_result;
    }

    public function orderProducts($order, $cart_id) { // Calculate Order Amount
        $errors = [];
        if ($cart_id != NULL) {
            foreach ($cart_id as $carts) {
                $cart = \common\models\Cart::findOne(['id' => $carts]);
                if ($cart != NULL) {
                    $orderProducts = new \common\models\OrderProducts();
                    $orderProducts->order_id = $order->id;
                    $orderProducts->user_id = $order->user_id;
                    $orderProducts->product_id = $cart->product_id;
                    $orderProducts->merchant_id = $cart->product->merchant_id;
                    $orderProducts->quantity = $cart->quantity;
                    $orderProducts->options = $cart->options;
                    $orderProducts->date = $cart->date;
                    $orderProducts->booking_slot = $cart->booking_slot;
                    $orderProducts->amount = Yii::$app->Products->price($cart->product);
                    $orderProducts->status = 1;   // 0- Abonded/Deleted, 1- Pending, 2-ORder Placed, 3- Shipped, 4-Delivered, 5- Returned , 6-Cancelled
                    $orderProducts->created_by = $order->user_id;
                    $orderProducts->updated_by = $order->user_id;
                    $orderProducts->updated_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    $orderProducts->created_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    if ($orderProducts->save()) {
                        $order_product_history = $this->addToHistory($orderProducts);
                        if ($order_product_history != NULL) {
                            $errors[] = $order_product_history;
                        }
                    } else {
                        $errors[] = $orderProducts->errors;
                    }
                }
            }
        }
        return $errors;
    }

    public function orderShipping($order, $datas) { // Calculate Order Amount
        $errors = [];
        if ($datas != NULL) {
            foreach ($datas as $data) {
                $merchant = \common\models\Merchant::findOne(['id' => $data['merchant_id']]);
                if ($merchant != NULL) {
                    $order_products = \common\models\OrderProducts::find()->where(['order_id' => $order->id, 'merchant_id' => $data['merchant_id']])->all();
                    if ($order_products != NULL) {
                        $shippingMethod = \common\models\MerchantShipmentMethods::find()->where(['merchant_id' => $data['merchant_id'], 'status' => 1, 'id' => $data['shipping_id']])->one();
                        if ($shippingMethod != NULL) {
                            $orderShipping = new \common\models\OrderShippingCharge();
                            $orderShipping->order_id = $order->id;
                            $orderShipping->merchant_id = $merchant->id;
                            $orderShipping->shipping_charge = $shippingMethod->price;
                            $orderShipping->shipping_method = $shippingMethod->id;
                            $orderShipping->created_by = $order->user_id;
                            $orderShipping->updated_by = $order->user_id;
                            $orderShipping->updated_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                            $orderShipping->created_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                            if ($orderShipping->save()) {

                            } else {
                                $errors[] = $orderShipping->errors;
                            }
                        } else {
                            $errors[] = "Shipping Method Not found";
                        }
                    } else {
                        $errors[] = "There no order products based on this user";
                    }
                } else {
                    $errors[] = "Invalid Merchant details";
                }
            }
        } else {
            $errors[] = "No merchant list found";
        }
        return $errors;
    }

    public function addToHistory($orderProducts) { // Calculate Order Amount
        $order_history_error = [];
        $order_history = new \common\models\OrderHistory();
        $order_history->order_id = $orderProducts->order_id;
        $order_history->order_product_id = $orderProducts->id;
        $order_history->order_status = 1;
        $order_history->status = 1;
        $order_history->created_by = $orderProducts->user_id;
        $order_history->updated_by = $orderProducts->user_id;
        $order_history->updated_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
        $order_history->created_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
        if ($order_history->save()) {

        } else {
            $order_history_error[] = $order_history->errors;
        }
        return $order_history_error;
    }

}
