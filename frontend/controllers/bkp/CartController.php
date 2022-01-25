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
class CartController extends Controller
{

    public $enableCsrfValidation = false;

    public static function allowedDomains()
    {
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
    public function behaviors()
    {
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
        //                    'actions' => ['get', 'post', 'index', 'delete'],
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

    public function init()
    {
        //        date_default_timezone_set('Asia/Qatar');
        parent::init();
    }

    public function actionIndex()
    {
        $a = 12;
        $name = "Partners";
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
                                        $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data['error'], 'cart');
                                        \Yii::$app->response->data = $array;
                                    } else if ($data['data'] != NULL) { //success
                                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'cart');
                                    } else { // NO data Found based  on request
                                        // \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'promotion');
                                        $arrayy = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, [], 'cart');
                                        $array['message'] = Yii::$app->ManageRequest->getMessage('cart_item_not_found', $lang);
                                        $array['status'] = 200;
                                        $array['data']['value'] = [];
                                        \Yii::$app->response->data = $array;
                                    }
                                } else { // NO data Found based  on request
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'cart');
                                }
                            } else { //Un autherised Auth Token
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'cart');
                            }
                        } else { //Un autherised Auth Token
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'cart');
                        }
                    } else { //Un autherised Auth Token
                        \Yii::$app->response->statusCode = 401;

                        $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'cart');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Auth token not provided
                    \Yii::$app->response->statusCode = 401;

                    $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'cart');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'cart');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'cart');
        }
    }

    private function getOptions($model, $lang)
    {
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

    public function post($headers, $post = [])
    { // post operation for creating reviews
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
        $params = ['product_id', 'quantity'];
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
                    // Any Error in the post data
                    $models = new \common\models\Cart();
                    if (!isset($post['product_id']) || $post['product_id'] == "0" || $post['product_id'] == "") {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'cart');
                        Yii::$app->end();
                    }
                    if (!isset($post['quantity']) || $post['quantity'] == "0" || $post['quantity'] == "") {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'cart');
                        Yii::$app->end();
                    }
                    $check_products = \common\models\ProductsServices::find()->where(['id' => $post['product_id']])->one();
                    if ($check_products == NULL) {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(450, $name, $lang, $post, (object) [], 'cart');
                        Yii::$app->end();
                    }
                    $attrqueryExist = \common\models\ProductAttributesValue::find()->where(['status' => 1, 'product_id' => $post['product_id']])->all();
                    if ($attrqueryExist != NULL) {

                        if (isset($post['options']) && $post['options'] != NULL && $post['options'] != "") {

                            $attributes = $this->getProductAttributes($post['product_id']);
                            $options = explode(',', $post['options']);
                            $errorCount = 0;
                            if ($options != NULL) {

                                if ($attributes != NULL) {

                                    foreach ($attributes as $attributeitems) {

                                        if (isset($attributeitems['attr_items']) && $attributeitems['attr_items'] != NULL) {
                                            $getAttrValLists = array_unique(array_column($attributeitems['attr_items'], 'id'));
                                            if ($getAttrValLists != NULL) {
                                                $result = array_intersect($getAttrValLists, $options);
                                                if (count($result) == 0) {
                                                    $errorCount++;
                                                }
                                            }
                                        }
                                    }
                                }
                                if ($errorCount > 0) {
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(460, $name, $lang, $post, (object) [], 'cart');
                                    //\Yii::$app->response->statusCode = 400;
                                    Yii::$app->end();
                                }
                            } else {
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(461, $name, $lang, $post, (object) [], 'cart');
                                //\Yii::$app->response->statusCode = 400;
                                Yii::$app->end();
                            }
                        } else {
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(461, $name, $lang, $post, (object) [], 'cart');
                            //\Yii::$app->response->statusCode = 400;
                            Yii::$app->end();
                        }
                    }
                    if ($check_products != NULL) {
                        $check_cart_query = \common\models\Cart::find()->where(['product_id' => $post['product_id'], 'user_id' => $userId]);
                        if ($check_products->type == 2 || $check_products->type == 3) {
                            if (isset($post['date']) && $post['date'] != "") {

                                $models->date = $post['date'];
                            } else {
                                $array = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'cart');
                                $array['message'] = Yii::$app->ManageRequest->getMessage('date_required', $lang);
                                \Yii::$app->response->data = $array;
                                Yii::$app->end();
                            }
                            if (isset($post['booking_slot']) && $post['booking_slot'] != "") {
                                $models->booking_slot = $post['booking_slot'];
                            } else {
                                $array = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'cart');
                                $array['message'] = Yii::$app->ManageRequest->getMessage('time_slots_required', $lang);
                                \Yii::$app->response->data = $array;
                                Yii::$app->end();
                            }
                            $days_availability = \common\models\WeekDaysAvailability::find()->where(['merchant_id' => $check_products->merchant_id, 'availability' => 1])->all();
                            if ($days_availability != NULL) {

                                if (isset($post['date']) && $post['date'] != "") {
                                    $check_cart_query->andWhere(['date' => $post['date']]);
                                }
                                if (isset($post['booking_slot']) && $post['booking_slot'] != "") {
                                    $check_cart_query->andWhere(['booking_slot' => $post['booking_slot']]);
                                }
                            }
                        }

                        if (isset($post['options'])) {
                            $check_cart_query->andWhere(['options' => $post['options']]);
                            $models->options = $post['options'];
                        }


                        $check_cart = $check_cart_query->one();
                        if ($check_cart != NULL) {
                            $models = $check_cart;
                            $models->quantity = $models->quantity + $post['quantity'];
                        } else {
                            $models->user_id = $userId;
                            $models->product_id = $post['product_id'];
                            $models->quantity = $post['quantity'];
                        }
                        $models->id = strtoupper(uniqid('AGOGO'));
                        $models->status = 1;
                        $models->created_by = $userId;
                        $models->updated_by = $userId;
                        $models->updated_by_type = 1;
                        $models->created_by_type = 1;
                        $models->scenario = "create_cart";
                        if ($models->save()) {
                            $transaction->commit();
                            $return = [
                                "id" => $models->id,
                                "name" => $lang == 1 ? $models->product->product_name_en : $models->product->product_name_ar,
                                "options" => $models->options,
                                "product_id" => $models->product_id,
                                "status" => $models->status,
                                "quantity" => $models->quantity,
                                "image" => $models->product->image != "" ? "uploads/products/" . base64_encode($models->product->sku) . "/image/" . $models->product->image : "img/no-image.jpg",
                                "price" => $models->product->price,
                                "sub_total" => Yii::$app->Currency->Convert((floatval($models->quantity * Yii::$app->Products->price($models->product))), $models->product->merchant->franchise_id, $lang),
                                "display_price" => Yii::$app->Products->price($models->product),
                            ];
                        } else {
                            $transaction->rollBack();

                            $errors_data = $models->getErrors();
                            foreach ($errors_data as $errors_dat) {
                                $errors[] = $errors_dat[0];
                            }
                        }
                    } else {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(449, $name, $lang, $post, $post, 'reviews');
                        Yii::$app->end();
                    }
                }
            } else {
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(449, $name, $lang, $post, $post, 'reviews');
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

    private function getProductAttributes($pid)
    {
        $attributes = [];
        $get_attributes = \common\models\ProductAttributesValue::find()
            ->select("product_attributes.*,product_attributes_value.id,attributes_value_id,price,attributes_value.value as attributes_value,attributes.name as name,attributes.id as attributes_id")
            ->where(['product_attributes_value.status' => 1, 'product_attributes_value.product_id' => $pid])
            ->andWhere('product_attributes.price_status != 1')
            ->innerJoinWith('attributesValue', false)
            ->join('LEFT OUTER JOIN', 'attributes', 'attributes_value.attributes_id =attributes.id')
            ->join('LEFT OUTER JOIN', 'product_attributes', 'product_attributes_value.product_attributes_id =product_attributes.id')
            ->orderBy(['product_attributes_value.sort_order' => SORT_ASC])
            ->asArray()->all();
        $attributes_lists = array_unique(array_column($get_attributes, 'attributes_id'));
        if ($attributes_lists != NULL) {
            foreach ($attributes_lists as $attributes_list) {
                $product_attr_items = [];
                foreach ($get_attributes as $get_attribute) {
                    if ($attributes_list == $get_attribute['attributes_id']) {
                        $name = $get_attribute['name'];

                        if (isset($get_attribute['name'])) {
                            unset($get_attribute['name']);
                        }
                        if (isset($get_attribute['attributes_id'])) {
                            unset($get_attribute['attributes_id']);
                        }
                        if (isset($get_attribute['attributes_value_id'])) {
                            unset($get_attribute['attributes_value_id']);
                        }
                        //                        if (isset($get_attribute['id'])) {
                        //                            $get_attribute['id'] = (int) $get_attribute['id'];
                        //                        }
                        array_push($product_attr_items, $get_attribute);
                    }
                }
                array_push($attributes, ['attribute_id' => (int) $attributes_list, 'attribute_name' => $name, 'attr_items' => $product_attr_items]);
            }
        }
        return $attributes;
    }

    public function put($headers, $post = [])
    { // post operation for creating reviews
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
        $params = ['id', 'product_id', 'status', 'options', 'quantity'];
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
                    // Any Error in the post data
                    if (!isset($post['product_id']) || $post['product_id'] == "0" || $post['product_id'] == "") {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'cart');
                        Yii::$app->end();
                    }
                    if (!isset($post['quantity']) || $post['quantity'] == "0" || $post['quantity'] == "") {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'cart');
                        Yii::$app->end();
                    }
                    $check_products = \common\models\ProductsServices::find()->where(['id' => $post['product_id']])->one();
                    if ($check_products == NULL) {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(450, $name, $lang, $post, (object) [], 'cart');
                        Yii::$app->end();
                    }
                    $check_cart_query = \common\models\Cart::find()->where(['id' => $post['id'], 'user_id' => $userId]);
                    if (isset($post['options'])) {
                        $check_cart_query->andWhere(['options' => $post['options']]);
                    }
                    $models = $check_cart_query->one();
                    if ($models == NULL) {
                        $array = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'cart');
                        $get_message = Yii::$app->ManageRequest->getMessage('cart_item_not_found', $lang); // changing the default success message to spesific message
                        $array['message'] = $get_message;
                        \Yii::$app->response->data = $array;
                        Yii::$app->end();
                    }
                    $models->options = $post['options'];
                    $models->quantity = $post['quantity'];
                    $models->status = $post['status'];
                    $models->created_by = $userId;
                    $models->updated_by = $userId;
                    $models->updated_by_type = 1;
                    $models->created_by_type = 1;
                    $models->scenario = "create_cart";

                    if ($check_products != NULL) {
                        $days_availability = \common\models\WeekDaysAvailability::find()->where(['merchant_id' => $check_products->merchant_id, 'availability' => 1])->all();
                        if ($days_availability != NULL) {
                            if (isset($post['date']) && $post['date'] != "") {

                                $models->date = $post['date'];
                            } else {
                                $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, (object) [], 'cart');
                                $array['message'] = Yii::$app->ManageRequest->getMessage('date_required', $lang);
                                \Yii::$app->response->data = $array;
                                Yii::$app->end();
                            }
                            if (isset($post['booking_slot']) && $post['booking_slot'] != "") {

                                $models->booking_slot = $post['booking_slot'];
                            } else {
                                $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, (object) [], 'cart');
                                $array['message'] = Yii::$app->ManageRequest->getMessage('time_slots_required', $lang);
                                Yii::$app->end();
                            }
                        }
                    }
                    if (!isset($post['status'])) {
                        if (($post['status'] != 1 && $post['status'] != 0)) {
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'cart');
                            Yii::$app->end();
                        }
                    }
                    $attrqueryExist = \common\models\ProductAttributesValue::find()->where(['status' => 1, 'product_id' => $post['product_id']])->all();
                    if ($attrqueryExist != NULL) {

                        if (isset($post['options']) && $post['options'] != NULL && $post['options'] != "") {

                            $attributes = $this->getProductAttributes($post['product_id']);
                            $options = explode(',', $post['options']);
                            $errorCount = 0;
                            if ($options != NULL) {

                                if ($attributes != NULL) {

                                    foreach ($attributes as $attributeitems) {

                                        if (isset($attributeitems['attr_items']) && $attributeitems['attr_items'] != NULL) {
                                            $getAttrValLists = array_unique(array_column($attributeitems['attr_items'], 'id'));
                                            if ($getAttrValLists != NULL) {
                                                $result = array_intersect($getAttrValLists, $options);
                                                if (count($result) == 0) {
                                                    $errorCount++;
                                                }
                                            }
                                        }
                                    }
                                }
                                if ($errorCount > 0) {
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(460, $name, $lang, $post, (object) [], 'cart');
                                    //\Yii::$app->response->statusCode = 400;
                                    Yii::$app->end();
                                }
                            } else {
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(461, $name, $lang, $post, (object) [], 'cart');
                                //\Yii::$app->response->statusCode = 400;
                                Yii::$app->end();
                            }
                        } else {
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(461, $name, $lang, $post, (object) [], 'cart');
                            //\Yii::$app->response->statusCode = 400;
                            Yii::$app->end();
                        }
                    }
                  
                    if ($models->save()) {
                        $transaction->commit();
                        $return = [
                            "id" => $models->id,
                            "name" => $lang == 1 ? $models->product->product_name_en : $models->product->product_name_ar,
                            "options" => $models->options,
                            "product_id" => $models->product_id,
                            "status" => $models->status,
                            "quantity" => $models->quantity,
                            "image" => $models->product->image != "" ? "uploads/products/" . base64_encode($models->product->sku) . "/image/" . $models->product->image : "img/no-image.jpg",
                            "price" => $models->product->price,
                            "sub_total" => Yii::$app->Currency->Convert((floatval($models->quantity * Yii::$app->Products->price($models->product))), $models->product->merchant->franchise_id, $lang),
                            "display_price" => Yii::$app->Products->price($models->product),
                        ];
                    } else {
                        $transaction->rollBack();

                        $errors_data = $models->getErrors();
                        foreach ($errors_data as $errors_dat) {
                            $errors[] = $errors_dat[0];
                        }
                    }
                }
            } else {
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(449, $name, $lang, $post, $post, 'reviews');
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

    public function get($headers, $post = [])
    { // Geting Data
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $finaldata = [];
        $return = [];
        $errors = [];
        $Id = Yii::$app->request->get('id'); // Getting Cart id from url
        $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        $offset = Yii::$app->request->get('offset'); // Getting 'offset' from url
        //Building query based on input get params
        // Add New Product to cart
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        $query = \common\models\Cart::find()->where(['user_id' => $userId]); // Getting The Cart items
        if (isset($limit) && $limit != "") {
            $query->limit($limit);
        }
        if (isset($offset) && $offset != "") {
            $offset = ($offset - 1) * $limit;

            $query->offset($offset);
        }
        if (isset($Id) && $Id != "") { //url carriying ID
            $query->andWhere(['id' => $Id]);
            $models = $query->one();
            if ($models != NULL) {
                $return = [
                    "id" => $models->id,
                    "name" => $lang == 1 ? $models->product->product_name_en : $models->product->product_name_ar,
                    "product_id" => $models->product_id,
                    "status" => $models->status,
                    "quantity" => $models->quantity,
                    "image" => $models->product->image != "" ? "uploads/products/" . base64_encode($models->product->sku) . "/image/" . $models->product->image : "img/no-image.jpg",
                    "price" => $models->product->price,
                    "sub_total" => Yii::$app->Currency->Convert((floatval($models->quantity * Yii::$app->Products->price($models->product))), $models->product->merchant->franchise_id, $lang),
                    "options" => $this->getOptions($models, $lang),
                    "date" => $models->date,
                    "booking_slot" => $models->booking_slot,
                    "display_price" => Yii::$app->Products->priceConvert($models->product, $lang),
                ];
            }
        } else { // not carrying
            $models_data = $query->all();
            if ($models_data != NULL) {
                foreach ($models_data as $models) {
                    $result = [
                        "id" => $models->id,
                        "name" => $lang == 1 ? $models->product->product_name_en : $models->product->product_name_ar,
                        "product_id" => $models->product_id,
                        "status" => $models->status,
                        "quantity" => $models->quantity,
                        "image" => $models->product->image != "" ? "uploads/products/" . base64_encode($models->product->sku) . "/image/" . $models->product->image : "img/no-image.jpg",
                        "price" => $models->product->price,
                        "sub_total" => Yii::$app->Currency->Convert((floatval($models->quantity * Yii::$app->Products->price($models->product))), $models->product->merchant->franchise_id, $lang),
                        "options" => $this->getOptions($models, $lang),
                        "date" => $models->date,
                        "booking_slot" => $models->booking_slot,
                        "display_price" => Yii::$app->Products->priceConvert($models->product, $lang),
                    ];
                    array_push($return, $result);
                }
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

    public function delete($headers, $post = [])
    { // Geting Data
        $name = "Delete Cart";
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $finaldata = [];
        $return = [];
        $errors = [];
        $Id = Yii::$app->request->get('id'); // Getting Cart id from url
        //Building query based on input get params
        // Add New Product to cart
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        if (!isset($Id) || $Id == "0" || $Id == "") {
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, (object) [], 'cart');
            Yii::$app->end();
        }
        $transaction = Yii::$app->db->beginTransaction();

        $model = \common\models\Cart::find()->where(['id' => $Id, 'user_id' => $userId])->one(); // Getting The Cart items
        if ($model != NULL) {
            if ($model->delete()) {
                $transaction->commit();
                $getquery = \common\models\Cart::find()->where(['user_id' => $userId]); // Getting The Cart items
                $models_data = $getquery->all();
                if ($models_data != NULL) {
                    foreach ($models_data as $models) {
                        $result = [
                            "id" => $models->id,
                            "name" => $lang == 1 ? $models->product->product_name_en : $models->product->product_name_ar,
                            "product_id" => $models->product_id,
                            "status" => $models->status,
                            "quantity" => $models->quantity,
                            "image" => $models->product->image != "" ? "uploads/products/" . base64_encode($models->product->sku) . "/image/" . $models->product->image : "img/no-image.jpg",
                            "price" => $models->product->price,
                            "options" => $this->getOptions($models, $lang),
                            "date" => $models->date,
                            "booking_slot" => $models->booking_slot,
                            "sub_total" => Yii::$app->Currency->Convert((floatval($models->quantity * $models->product->price)), $models->product->merchant->franchise_id, $lang),
                            "display_price" => Yii::$app->Products->priceConvert($models->product, $lang),
                        ];
                        array_push($return, $result);
                    }
                }
                //  $finaldata['data'] = $return;
                if ($return != NULL) {

                    $array = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $return, 'cart');
                    $array['message'] = Yii::$app->ManageRequest->getMessage('cart_item_delete_success', $lang);
                    \Yii::$app->response->data = $array;
                    Yii::$app->end();
                } else {
                    $arrayy = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, [], 'cart');
                    $array['message'] = Yii::$app->ManageRequest->getMessage('cart_item_delete_success', $lang);
                    $array['status'] = 200;
                    $array['data']['value'] = [];
                    \Yii::$app->response->data = $array;
                    Yii::$app->end();
                }
            } else {
                $transaction->rollBack();
                $errors_data = $model->getErrors();
                foreach ($errors_data as $errors_dat) {
                    $errors[] = $errors_dat[0];
                }
            }
        } else {
            $errors['id'] = Yii::$app->ManageRequest->getMessage('cart_item_not_found', $lang);
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }
}
