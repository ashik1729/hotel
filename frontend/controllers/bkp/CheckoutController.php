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
class CheckoutController extends Controller {

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
//                    'actions' => ['get', 'post', 'index'],
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

    public function actionIndex() {

        $name = "Profile";
        header('Content-type:appalication/json'); //Header content set to json
        $headers = Yii::$app->request->headers;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Set output format structure
        if (isset($headers['lang']) && $headers['lang'] != "") { //setting App language
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $data = [];
        $json = file_get_contents('php://input'); // Getting Post Data
        $post = json_decode($json, true); // Converting into Array
        if (isset($headers['accessToken']) && $headers['accessToken'] != "") { // Chech the Access token is set or not
            if (Yii::$app->ManageRequest->validateAccessToken($headers['accessToken'])) { // Validate Acccess Token
                if (isset($headers['authToken']) && $headers['authToken'] != "") { //Check the AuthToken Is Set
                    if (Yii::$app->ManageRequest->validateAuthToken($headers['authToken']) != NULL) { // Check The authToken is Valids
                        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
                        $usermodel = \common\models\User::findOne(['id' => $userId]);

                        if ($usermodel != NULL) { //check usrr exist
                            if ($usermodel->user_type != 3) { // check it is not a guest
                                $action = strtolower($_SERVER['REQUEST_METHOD']); // Getting action from request header
                                $data = $this->$action($headers, $post); // Call respective action with post data and headers
                                if ($data != NULL) { // check the result have value
                                    if ($data['error'] != NULL) { // Error Found on the reques
                                        $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'checkout');
                                        \Yii::$app->response->data = $array;
                                    } else if ($data['data'] != NULL) { //success
                                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'checkout');
                                    } else { // NO data Found based  on request
                                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'checkout');
                                    }
                                } else { // NO data Found based  on request
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'checkout');
                                }
                            } else { //Un autherised Auth Token
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'checkout');
                            }
                        } else { //Un autherised Auth Token
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'checkout');
                        }
                    } else { //Un autherised Auth Token
                        \Yii::$app->response->statusCode = 401;

                        $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'checkout');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Auth token not provided
                    \Yii::$app->response->statusCode = 401;

                    $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'checkout');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'checkout');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'checkout');
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
        $params = ['cart_items'];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        $store_id = \common\models\Franchise::find()->where(['access_token' => $headers['accessToken'], 'status' => 10])->one()->id;

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
                $carts = \common\models\Cart::find()->where(['id' => $post['cart_items'], 'status' => 1])->all();
                $cartItems = [];
                $merchantLists = [];
                if ($carts != NULL) {

                    foreach ($carts as $cart) {
                        array_push($merchantLists, $cart->product->merchant_id);
                    }
                    $merchantLists = array_filter(array_unique($merchantLists));
                    if ($merchantLists != NULL) {
                        foreach ($merchantLists as $merchantList) {
                            $merchant = \common\models\Merchant::findOne(['id' => $merchantList]);
                            if ($merchant != NULL) {
                                $items = [];
                                foreach ($carts as $cart) {
                                    if ($cart->product->merchant_id == $merchantList) {
                                        $ItemData = [
                                            "id" => $cart->id,
                                            "name" => $lang == 1 ? $cart->product->product_name_en : $cart->product->product_name_ar,
                                            "product_id" => $cart->product_id,
                                            "status" => $cart->status,
                                            "quantity" => $cart->quantity,
                                            "image" => $cart->product->image != "" ? "uploads/products/" . base64_encode($cart->product->sku) . "/image/" . $cart->product->image : "img/no-image.jpg",
                                            "price" => $cart->product->price,
                                            "sub_total" => Yii::$app->Currency->Convert((floatval($cart->quantity * $cart->product->price)), $cart->product->merchant->franchise_id, $lang),
                                            "options" => $this->getOptions($cart, $lang),
                                            "display_price" => Yii::$app->Products->priceConvert($cart->product, $lang),
                                        ];
                                        array_push($items, $ItemData);
                                    }
                                }
                                $dafault = \common\models\MerchantShipmentMethods::find()->where(['merchant_id' => $merchant->id, 'status' => 1, 'defaultShipment' => 1])->one();
                                if ($dafault == NULL) {
                                    $dafault = \common\models\MerchantShipmentMethods::find()->where(['merchant_id' => $merchant->id, 'status' => 1])->one();
                                }
                                $cartData = [
                                    "merchant_id" => $merchant->id,
                                    "merchant_name" => $merchant ? ($lang == 1 ? $merchant->business_name : $merchant->business_name_ar) : "",
                                    "shippingMethods" => $this->getshippingMethods($merchant, 0, $lang),
                                    "items" => $items,
                                    "totalShippingCharge" => $dafault != NULL ? floatval($dafault->price) : floatval(0),
                                ];
                                array_push($cartItems, $cartData);
                            }
                        }
                    }
                }

                $shipping_charge = 0;
                if ($cartItems != NULL) {
                    $shippingChargeList = array_column($cartItems, 'totalShippingCharge');
                    if ($shippingChargeList != NULL) {
                        $shipping_charge = array_sum($shippingChargeList);
                    }
                }
                $model = \common\models\UserAddress::find()->where(['user_id' => $userId])->one(); // Check the review is approved by admin
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
                        "street_address" => $model->streat_address,
                        "zipcode" => $model->postcode,
                        "phone_number" => $model->phone_number,
                        "email" => $model->email,
                    ];
                }
                $coupon = 0;
                $tax = 0;
                $return = [//return files
                    "cartItems" => $cartItems,
                    "paymentMethods" => $this->getpaymentMethods(count($merchantLists), $lang),
                    "shippingCharge" => Yii::$app->Currency->Convert(floatval($shipping_charge), $store_id, $lang),
                    "subTotal" => Yii::$app->Currency->Convert($this->subTotal($carts), $store_id, $lang),
                    "grandTotal" => Yii::$app->Currency->Convert($this->grandTotal($carts, $shipping_charge, $coupon, $tax), $store_id, $lang),
                    "discount" => Yii::$app->Currency->Convert(floatval(0), $store_id, $lang),
                    "couponCode" => "",
                    "addressBook" => $address_array,
                ];
            }
        } else {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(449, $name, $lang, $post, $post, 'checkout');
            Yii::$app->end();
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

    function flatten(array $array) {
        $return = array();
        array_walk_recursive($array, function($a) use (&$return) {
            $return[] = $a;
        });
        return $return;
    }

    public function put($headers, $post = []) { // post operation for creating checkout
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
        $params = ['data'];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        $store_id = \common\models\Franchise::find()->where(['access_token' => $headers['accessToken'], 'status' => 10])->one()->id;

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
                if ($post['data'] != NULL) {
                    $cartItems = [];
                    foreach ($post['data'] as $data) {
                        $carts = \common\models\Cart::find()->where(['id' => $data['cart_items'], 'status' => 1])->all();
                        $merchant = \common\models\Merchant::findOne(['id' => $data['merchant_id']]);

                        if ($merchant != NULL) {
                            $items = [];
                            foreach ($carts as $cart) {
                                if ($cart->product->merchant_id == $data['merchant_id']) {
                                    $ItemData = [
                                        "id" => $cart->id,
                                        "name" => $lang == 1 ? $cart->product->product_name_en : $cart->product->product_name_ar,
                                        "product_id" => $cart->product_id,
                                        "status" => $cart->status,
                                        "quantity" => $cart->quantity,
                                        "image" => $cart->product->image != "" ? "uploads/products/" . base64_encode($cart->product->sku) . "/image/" . $cart->product->image : "img/no-image.jpg",
                                        "price" => $cart->product->price,
                                        "sub_total" => Yii::$app->Currency->Convert((floatval($cart->quantity * $cart->product->price)), $cart->product->merchant->franchise_id, $lang),
                                        "options" => $this->getOptions($cart, $lang),
                                        "display_price" => Yii::$app->Products->priceConvert($cart->product, $lang),
                                    ];
                                    array_push($items, $ItemData);
                                }
                            }
                            $dafault = \common\models\MerchantShipmentMethods::find()->where(['merchant_id' => $merchant->id, 'status' => 1, 'id' => $data['shipping_id']])->one();
                            if ($dafault == NULL) {
                                $dafault = \common\models\MerchantShipmentMethods::find()->where(['merchant_id' => $merchant->id, 'status' => 1])->one();
                            }
                            $cartData = [
                                "merchant_id" => $merchant->id,
                                "merchant_name" => $merchant ? ($lang == 1 ? $merchant->business_name : $merchant->business_name_ar) : "",
                                "shippingMethods" => $this->getshippingMethods($merchant, $data['shipping_id'], $lang),
                                "items" => $items,
                                "totalShippingCharge" => $dafault != NULL ? floatval($dafault->price) : floatval(0),
                            ];
                            array_push($cartItems, $cartData);
                        }
                    }
                }


                $shipping_charge = 0;
                if ($cartItems != NULL) {
                    $shippingChargeList = array_column($cartItems, 'totalShippingCharge');
                    if ($shippingChargeList != NULL) {
                        $shipping_charge = array_sum($shippingChargeList);
                    }
                }
                $model = \common\models\UserAddress::find()->where(['user_id' => $userId])->one(); // Check the review is approved by admin
                $address_array = [];
//t
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
                        "street_address" => $model->streat_address,
                        "zipcode" => $model->postcode,
                        "phone_number" => $model->phone_number,
                        "email" => $model->email,
                    ];
                }
                $cartIdlist = array_column($post['data'], 'cart_items');
                $cartIds = $this->flatten($cartIdlist);
                $cartExist = [];
                if ($cartIds != NULL) {
                    foreach ($cartIds as $cartId) {
                        $getCart = \common\models\Cart::find()->where(['id' => $cartId, 'status' => 1])->one();
                        if ($getCart == NULL) {
                            $cartExist[] = "Cart Ref :" . $cartId . " Not Available in the records";
                        }
                    }
                }
                if ($cartExist != NULL) {
                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $cartExist, 'checkout');
                    Yii::$app->end();
                }
                $getCartslist = \common\models\Cart::find()->where(['id' => $cartIds, 'status' => 1])->all();
                if (isset($post['coupon_code']) && $post['coupon_code'] != "") {
                    $coupondata = $this->getCoupon($post);
                    if ($coupondata['status'] && $coupondata['status'] == TRUE) {
                        $coupon = $coupondata['amount'];
                    } else {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(458, $name, $lang, $post, [], 'checkout');
                        Yii::$app->end();
                    }
                } else {
                    $coupon = 0;
                }
                $tax = 0;
                $return = [//return files
                    "cartItems" => $cartItems,
                    "paymentMethods" => $this->getpaymentMethods(count($post['data']), $lang),
                    "shippingCharge" => Yii::$app->Currency->Convert(floatval($shipping_charge), $store_id, $lang),
                    "subTotal" => Yii::$app->Currency->Convert($this->subTotal($getCartslist), $store_id, $lang),
                    "grandTotal" => Yii::$app->Currency->Convert($this->grandTotal($getCartslist, $shipping_charge, $coupon, $tax), $store_id, $lang),
                    "discount" => Yii::$app->Currency->Convert(floatval($coupon), $store_id, $lang),
                    "couponCode" => $post['coupon_code'] ? $post['coupon_code'] : "",
                    "addressBook" => $address_array,
                ];
            }
        } else {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(449, $name, $lang, $post, $post, 'checkout');
            Yii::$app->end();
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

    private function getshippingMethods($model, $shipp_id, $lang) {
        $return_result = [];
        if ($model != "") {
            $get_options = \common\models\MerchantShipmentMethods::find()->where(['merchant_id' => $model->id, 'status' => 1])->all();
            if ($get_options != NULL) {
                $dafault = \common\models\MerchantShipmentMethods::find()->where(['merchant_id' => $model->id, 'status' => 1, 'defaultShipment' => 1])->one();
                if ($dafault == NULL) {
                    $dafault = \common\models\MerchantShipmentMethods::find()->where(['merchant_id' => $model->id, 'status' => 1])->one();
                }
                foreach ($get_options as $get_option) {
                    array_push($return_result, [
                        'shipping_method_id' => $get_option->id,
                        'shipping_name' => $lang == 1 ? $get_option->shippment->name_en : $get_option->shippment->name_ar,
                        'shipping_price' => $get_option->price,
                        'shipping_default' => $shipp_id != 0 ? ($get_option->id == $shipp_id ? TRUE : FALSE) : ( $dafault->id == $get_option->id ? TRUE : FALSE),
                    ]);
                }
            }
        }
        return $return_result;
    }

    private function getpaymentMethods($merchantCount, $lang) {
        $return_result = [];
//        if ($model != "") {
        $get_options = [
            [
                'payment_type' => 1,
                'payment_name' => Yii::$app->ManageRequest->getMessage('card', $lang),
                'payment_type_status' => TRUE
            ],
            [
                'payment_type' => 2,
                'payment_name' => Yii::$app->ManageRequest->getMessage('cash-on-delivery', $lang),
                'payment_type_status' => $merchantCount > 1 ? FALSE : TRUE
            ],
            [
                'payment_type' => 3,
                'payment_name' => Yii::$app->ManageRequest->getMessage('card-on-delivery', $lang),
                'payment_type_status' => $merchantCount > 1 ? FALSE : TRUE
            ],
        ];
        if ($get_options != NULL) {
            foreach ($get_options as $get_option) {
                array_push($return_result, $get_option);
            }
        }
//        }
        return $return_result;
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
                            'option_id' => $get_option,
                            'option_type' => $lang == 1 ? $option_details->attributesValue->attributes0->name : $option_details->attributesValue->attributes0->name_ar,
                            'option_value' => $option_details->attributesValue->value,
                        ]);
                    }
                }
            }
        }
        return $return_result;
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
                        $getCartslist = \common\models\Cart::find()->where(['id' => $cartIds, 'status' => 1])->all();
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
                $cart = \common\models\Cart::findOne(['id' => $carts, 'status' => 1]);
                if ($cart != NULL) {
                    $total_amount += ($cart->quantity * Yii::$app->Products->Price($cart->product));
                }
            }
        }

        return floatval($total_amount);
    }

//    public function taxCalc($carts, $shippingCharge, $codCharge) { // Calculate Order Amount
//        $subtotal = 0;
//        $tax = 0;
//        $taxRate = \common\models\TaxClass::findOne(['id' => 1]);
//        if ($carts != NULL) {
//            foreach ($carts as $carts) {
//                $cart = \common\models\Cart::findOne(['id' => $carts]);
//                if ($cart != NULL) {
//                    $subtotal += ($cart->quantity * Yii::$app->Products->discountPrice($cart->product));
//                }
//            }
//        }
//
//        if ($taxRate != NULL) {
//            $tax = ($subtotal + $shippingCharge + $codCharge) * $taxRate->tax_rate;
//        }
//        return round($tax, 2);
//    }

    public function grandTotal($carts, $shipping_charge, $coupon, $tax = 0) { // Calculate Order Amount
        $subtotal_amount = 0;
        if ($carts != NULL) {
            foreach ($carts as $carts) {
                $cart = \common\models\Cart::findOne(['id' => $carts, 'status' => 1]);
                if ($cart != NULL) {
                    $subtotal_amount += ($cart->quantity * Yii::$app->Products->price($cart->product));
                }
            }
        }

        $granttotal_amount = ($subtotal_amount + $shipping_charge + $tax) - $coupon;

        return $granttotal_amount > 0 ? round($granttotal_amount, 2) : floatval(0);
    }

    public function calculateOrderAmount($cart_id) { // Calculate Order Amount
        $total_amount = 0;
        if ($cart_id != NULL) {
            foreach ($cart_id as $carts) {
                $cart = \common\models\Cart::findOne(['id' => $carts, 'status' => 1]);
                if ($cart != NULL) {
                    $total_amount += Yii::$app->Products->price($cart->product);
                }
            }
        }
        return $total_amount;
    }

    public function getShippingCharge($cart_id) { // Calculate Order Amount
        $shipping_charge = 0;
        if ($cart_id != NULL) {
            foreach ($cart_id as $carts) {
                $cart = \common\models\Cart::findOne(['id' => $carts, 'status' => 1]);
                if ($cart != NULL) {
                    if (isset($cart->product->merchant->shipping_charge)) {
                        $shipping_charge += $cart->product->merchant->shipping_charge;
                    }
                }
            }
        }

        return $shipping_charge;
    }

    public function orderProducts($order, $cart_id) { // Calculate Order Amount
        $errors = [];
        if ($cart_id != NULL) {
            foreach ($cart_id as $carts) {
                $cart = \common\models\Cart::findOne(['id' => $carts, 'status' => 1]);
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

    public function orderShipping($order) { // Calculate Order Amount
        $errors = [];
        $order_products = \common\models\OrderProducts::find()->select('merchant_id')->where(['order_id' => $order->id])->asArray()->all();
        $merchant_lists = array_unique(array_column($order_products, 'merchant_id'));
        if ($merchant_lists != NULL) {
            foreach ($merchant_lists as $merchant_list) {
                $merchant = \common\models\Merchant::findOne(['id' => $merchant_list]);
                $orderShipping = new \common\models\OrderShippingCharge();
                $orderShipping->order_id = $order->id;
                $orderShipping->merchant_id = $merchant->id;
                $orderShipping->shipping_charge = $merchant->shipping_charge;
                $orderShipping->created_by = $order->user_id;
                $orderShipping->updated_by = $order->user_id;
                $orderShipping->updated_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                $orderShipping->created_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                if ($orderShipping->save()) {

                } else {
                    $errors[] = $orderShipping->errors;
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
