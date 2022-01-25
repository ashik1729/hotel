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
class SearchController extends Controller {

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
//                    'actions' => ['post', 'index'],
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
    }

    public function actionIndex() {

        $name = "Search";
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
                        $transaction = Yii::$app->db->beginTransaction();
                        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
                        $usermodel = \common\models\User::findOne(['id' => $userId]);

                        if ($usermodel != NULL) { //check usrr exist
//                            if ($usermodel->user_type != 3) { // check it is not a guest
                            $action = strtolower($_SERVER['REQUEST_METHOD']); // Getting action from request header
                            $data = $this->$action($headers, $post); // Call respective action with post data and headers
                            if ($data != NULL) { // check the result have value
                                if ($data['error'] != NULL) { // Error Found on the reques
                                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data['error'], 'search');
                                    \Yii::$app->response->data = $array;
                                } else if ($data['data'] != NULL) { //success
                                    $check_seach_key_exist = $this->addToSearchHistory($headers, $post);
                                    if ($check_seach_key_exist['error'] != NULL) {
                                        $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $check_seach_key_exist['error'], 'search');
                                        \Yii::$app->response->data = $array;
                                    } else {
                                        $transaction->commit();
                                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'search');
                                    }
                                } else { // NO data Found based  on request
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'search');
                                }
                            } else { // NO data Found based  on request
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'search');
                            }
//                            } else { //Un autherised Auth Token
//                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'search');
//                            }
                        } else { //Un autherised Auth Token
                            \Yii::$app->response->statusCode = 401;
                            $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'search');
                            \Yii::$app->response->data = $array;
                        }
                    } else { //Un autherised Auth Token
                        \Yii::$app->response->statusCode = 401;
                        $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], Yii::$app->ManageRequest->validateAuthToken($headers['authToken']), 'search');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Auth token not provided
                    $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'search');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'search');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'search');
        }
    }

    public function post($headers, $post = []) { // post operation for creating reviews
        $name = "Search";
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $finaldata = [];
        $return = [];
        $returndata = [];
        $new_result = [];
        $errors = [];
        $limit = Yii::$app->ManageRequest->getVariable('search_list_item_per_page');

        if (Yii::$app->request->get('limit')) {
            $limit = Yii::$app->request->get('limit'); // Getting 'limit' from url
        }
        $offset = Yii::$app->request->get('offset');
        $params = ["item_type", "type_of_search"];
//        'type_of_search', 'radius', 'category',
        //Type_of_search = 1-Current Location And Proximity,2- By Map,3-By Name
        //radius - Radius of search
        //Category - Business Category
        //Item_type  - products or service
        //search_key  - Search text
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
                        $errors[$param] = "";
                    }
                }
            }
            if ($errors == NULL) { // Any Error in the post data
                if (isset($post['item_type']) && $post['item_type'] && $post['item_type'] != "") { //item_type may be 1(Store/merchant ) or Products
                    $item_type = $post['item_type'];
                    if ($item_type == 1) {
                        $query = \common\models\Merchant::find();

                        if (isset($post['type_of_search'])) { // 1-Current Location And Proximity,2- By Map
                            if ($post['type_of_search'] == 1 || $post['type_of_search'] == 2) { // 1-Current Location And Proximity,2- By Map
                                $query = $this->getDataByProximityStore($post, $query, $store_id);
                            } else if ($post['type_of_search'] == 3) {//3-Name and Cateogry
                                $query = $this->getDataByNameStore($post, $query, $store_id);
                            } else {
                                $query = $this->getDataByNameStore($post, $query, $store_id);
                            }
                        } else {
                            $query = $this->getDataByNameStore($post, $query, $store_id);
                        }
                    } else if ($item_type == 2) {
                        $query = \common\models\ProductsServices::find();
                        if (isset($post['merchant_id']) && $post['merchant_id'] && $post['merchant_id'] != "0") {
                            $query->andWhere(['merchant_id' => $post['merchant_id']]);
                        }
                        if (isset($post['type_of_search'])) { // 1-Current Location And Proximity,2- By Map
                            if ($post['type_of_search'] == 1 || $post['type_of_search'] == 2) { // 1-Current Location And Proximity,2- By Map
                                $query = $this->getDataByProximityItem($post, $query, $store_id);
                            } else if ($post['type_of_search'] == 3) {//3-Name and Cateogry
                                $query = $this->getDataByNameItem($post, $query, $store_id);
                            } else {
                                $query = $this->getDataByNameItem($post, $query, $store_id);
                            }
                        } else {
                            $query = $this->getDataByNameItem($post, $query, $store_id);
                        }
                    }
                }

                if ($post['type_of_search'] == 1 || $post['type_of_search'] == 3) { // 1-Current Location And Proximity,2- By Map
                    if (isset($limit) && $limit != "") {
                        $query->limit($limit);
                    }
                    if (isset($offset) && $offset != "") {
                        $offset = ($offset - 1) * $limit;
                        $query->offset($offset);
                    }
                }

                $models_data = $query->asArray()->all();
                if ($models_data != NULL) {
                    foreach ($models_data as $models) {
                        $merchant = \common\models\Merchant::findOne($models['merchant_id']);

                        if ($merchant != NULL) {
                            if ($post['item_type'] == 1) { //1-store/merchant,2-product/service
                                $result = [
//                            "availability" => $models['availability_exist'],
                                    "id" => $models['merchant_id'],
                                    "name" => $lang == 1 ? $merchant->business_name : $merchant->business_name_ar,
                                    "merchant_average_rating" => isset($models['total_rating_count']) ? ($models['total_rating_count'] == 0 ? strval(5) : ( isset($models['average_rating']) ? strval($models['average_rating']) : strval(5))) : strval(5),
                                    "latitude" => isset($models['latitude']) ? $models['latitude'] : "",
                                    "longitude" => isset($models['longitude']) ? $models['longitude'] : "",
                                    "distance" => isset($models['distance']) ? $models['distance'] : "",
                                    "description" => "",
                                    "availability_status" => $merchant->availability,
                                    "review_status" => $merchant->productReviews != NULL ? "1" : "0",
                                    "reviews" => $merchant->productReviews,
                                    "review_rating" => $merchant->rating() <= 0 ? strval(5) : $merchant->rating(),
                                    "city" => $merchant->city,
                                    "city_name" => $merchant->city0 ? ($lang == 1 ? $merchant->city0->name_en : $merchant->city0->name_ar) : "",
                                    "category" => $merchant->type_of_business,
                                    "category_name" => $merchant->businessType ? ($lang == 1 ? $merchant->businessType->category_name_en : $merchant->businessType->category_name_ar) : "",
                                    "image" => $merchant->business_logo != "" ? 'uploads/merchant/' . $merchant->id . '/logo/large/' . $merchant->business_logo : "img/no-image.jpg",
                                ];
                            } else if ($post['item_type'] == 2) { //
                                $pmodel = \common\models\ProductsServices::findOne($models['pid']);
                                if ($pmodel != NULL) {
                                    $result = [
//                            "availability" => $models['availability_exist'],
                                        "id" => $models['pid'],
                                        "merchant_id" => $models['merchant_id'],
                                        "merchant_name" => $lang == 1 ? $merchant->business_name : $merchant->business_name_ar,
                                        "merchant_average_rating" => isset($models['total_rating_count']) ? ($models['total_rating_count'] == 0 ? strval(5) : ( isset($models['average_rating']) ? strval($models['average_rating']) : strval(5))) : strval(5),
                                        "store_image" => $merchant->business_logo != "" ? 'uploads/merchant/' . $merchant->id . '/logo/large/' . $merchant->business_logo : "img/no-image.jpg",
                                        "latitude" => isset($models['latitude']) ? $models['latitude'] : "",
                                        "longitude" => isset($models['longitude']) ? $models['longitude'] : "",
                                        "distance" => isset($models['distance']) ? $models['distance'] : "",
                                        "name" => $lang == 1 ? $models['product_name_en'] : $models['product_name_ar'],
                                        "description" => $lang == 1 ? $models['short_description_en'] : $models['short_description_ar'],
                                        "price" => Yii::$app->Products->priceConvert($pmodel, $lang),
//                                    "availability_status" => $models['availability_status'],
                                        "availability_status" => $merchant->availability,
                                        "product_type" => $models['type'], //1-Product,2-Home Service,3-Shop Service
                                        "discount_from" => $models['discount_from'],
                                        "discount_to" => $models['discount_to'],
                                        "discount_rate" => $models['discount_rate'],
                                        "display_price" => Yii::$app->Products->priceConvert($pmodel, $lang),
                                        "discount_type" => $models['discount_type'], // 1-FLat Rate,2-Percentage
                                        "discount_type_name" => $models['discount_type'] == 1 ? "Flat Rate" : "Percentage", // 1-FLat Rate,2-Percentage
                                        "review_status" => $pmodel->productReviews != NULL ? "1" : "0",
                                        "reviews" => $pmodel->productReviews,
                                        "review_rating" => $pmodel->rating() <= 0 ? strval(5) : $pmodel->rating(),
                                        "favourite_status" => $pmodel->getMyFavourite($userId) != NULL ? "1" : "0",
                                        "favourite" => $pmodel->getMyFavourite($userId),
                                        "category" => $pmodel->category->id,
                                        "city_name" => $pmodel->merchant->city0 ? ($lang == 1 ? $pmodel->merchant->city0->name_en : $pmodel->merchant->city0->name_ar) : "",
                                        "category_name" => $pmodel->category ? ($lang == 1 ? $pmodel->category->category_name : $pmodel->category->category_name_ar) : "",
                                        "type_name" => $models['type'] == 1 ? "Product" : ($models['type'] == 2 ? "Shop Service" : ($models['type'] == 3 ? "Online Service" : "")),
                                        "image" => $models['image'] != "" ? "uploads/products/" . base64_encode($models['sku']) . "/image/large/" . $models['image'] : "img/no-image.jpg",
                                    ];
                                }
                            }
                        }
                        array_push($returndata, $result);
                    }

//                    $new_result = array_values(array_unique($returndata, SORT_REGULAR));
//                    if (isset($offset) && $offset > 0) {
//                        $new_result = array_slice($new_result, $limit, $offset);   // returns "a", "b", and "c"
//                    }
                }
                $return['items'] = $returndata;
            }
        }
        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata; // return error and data
    }

    private function getDataByProximityItem($post, $query, $store_id) {
        $sql_distance = "products_services.id as pid,merchant.availability as availability_status,merchant.availability_from as availability_from,merchant.availability_to as availability_to,products_services.*,merchant.latitude,merchant.longitude";
        $having = "";

        if (isset($post['latitude']) && $post['latitude'] != "" && isset($post['longitude']) && $post['longitude'] != "") {  // check lattitude and longitude is set.
            $latitude = $post['latitude'];
            $longitude = $post['longitude'];
            $radius = $post['radius'];
            $sql_distance .= ", (((acos(sin((" . $latitude . "*pi()/180)) * sin((`merchant`.`latitude`*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((`merchant`.`latitude`*pi()/180)) * cos(((" . $longitude . "-`merchant`.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance "; //Getting value based on given input location cordinates
            if (isset($post['radius']) && $post['radius'] != '') {
                $having = "(distance <= $radius) ";
            }
            $query->orderBy([
                'distance' => SORT_ASC,
            ]);
        }
        $query->select($sql_distance);
        $query->from('products_services');
        if ($having != "") {
            $query->having($having);
        }

        $query->innerJoinWith('merchant', false);
        if (isset($post['search_key']) && $post['search_key'] != "") {
            $query->andWhere(['LIKE', 'product_name_en', $post['search_key']]);
            $query->orWhere(['LIKE', 'products_services.search_tag', $post['search_key']]);
        }
        if ($post['type_of_search'] == 2) {
            if (isset($post['city']) && $post['city'] != "" && $post['city'] != "0") {
                $query->andWhere(['merchant.city' => $post['city']]);
            }
            if (isset($post['area']) && $post['area'] != "") {
                $query->andWhere(['merchant.area' => $post['area']]);
            }
        }
        $query->andWhere(['products_services.status' => 1, 'merchant.status' => 10]);
        $query->andWhere(['merchant.franchise_id' => $store_id->id]);
        if (isset($post['category']) && $post['category'] != "" && $post['category'] != "0") {
            $query->andWhere(new \yii\db\Expression('FIND_IN_SET(:cat_to_find,merchant.category)'))
                    ->addParams([':cat_to_find' => $post['category']]);
        }
        return $query;
    }

    private function getDataByNameItem($post, $query, $store_id) {
// Geting Product list based on rating and availability
        $sql = "products_services.id as pid,merchant.*,products_services.*,merchant.availability as availability_status,merchant.availability_from as availability_from,merchant.availability_to as availability_to, merchant.latitude,merchant.longitude";
        $having = [];
//        if (isset($post['latitude']) && $post['latitude'] != "" && isset($post['longitude']) && $post['longitude'] != "") {  // check lattitude and longitude is set.
//            $latitude = $post['latitude'];
//            $longitude = $post['longitude'];
//            if (isset($post['radius']) && $post['radius'] != '') {
//                $radius = $post['radius'];
//            } else {
//                $radius = "";
//            }
//
//            $sql .= " , (((acos(sin((" . $latitude . "*pi()/180)) * sin((`merchant`.`latitude`*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((`merchant`.`latitude`*pi()/180)) * cos(((" . $longitude . "-`merchant`.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance "; //Getting value based on given input location cordinates
//            if (isset($post['radius']) && $post['radius'] != '') {
//                $having[] = "(distance <= $radius) ";
//            }
//            $query->orderBy(['distance' => SORT_ASC]);
//
//        }
        $current_date = date('Y-m-d');
        $current_day = date("l", strtotime($current_date));
        if (isset($post['rating']) && $post['rating'] != "" && $post['rating'] != "0") {
            $sql .= ',(SELECT COUNT(*) FROM product_review WHERE product_review.review_for_id=products_services.id AND (product_review.review_type = 1  OR product_review.review_type = 2)) as total_rating_count,(SELECT SUM(product_review.rating) FROM product_review WHERE product_review.review_for_id=products_services.id AND (product_review.review_type = 1  OR product_review.review_type = 2))/(SELECT COUNT(*) FROM product_review WHERE product_review.review_for_id = products_services.id AND (product_review.review_type = 1  OR product_review.review_type = 2)) as average_rating';
            $having[] = "(total_rating_count = 0 OR average_rating >=  " . $post['rating'] . ")";
            $query->orderBy(['average_rating' => SORT_DESC]);
        }
//        if (isset($post['availability']) && $post['availability'] != "" && $post['availability'] == 1) {
//            $sql .= ',(SELECT COUNT(*) FROM week_days_availability WHERE week_days_availability.merchant_id = products_services.merchant_id AND week_days_availability.availability =1 AND (DATE(date) = "' . $current_date . '" OR day = "' . $current_day . '" ))  as availability_exist';
//        }
        $query->select($sql);
        $query->innerJoinWith('merchant', false);
        //$query->join('LEFT OUTER JOIN', 'product_review', 'product_review.review_for_id =products_services.id AND (product_review.review_type =1 OR product_review.review_type =2 )');

        if (isset($post['search_key']) && $post['search_key'] != "") {
            $query->andWhere(['LIKE', 'products_services.product_name_en', $post['search_key']]);
            $query->orWhere(['LIKE', 'products_services.search_tag', $post['search_key']]);
            $query->orWhere(['LIKE', 'merchant.business_name', $post['search_key']]);
            $query->orWhere(['LIKE', 'merchant.search_tag', $post['search_key']]);
        }
//        if (isset($post['city']) && $post['city'] != "") {
//            $query->andWhere(['merchant.city' => $post['city']]);
//        }
//        if (isset($post['area']) && $post['area'] != "") {
//            $query->andWhere(['merchant.area' => $post['area']]);
//        }
        $query->andWhere(['merchant.status' => 10]);
        $query->andWhere(['merchant.franchise_id' => $store_id->id]);
        if (isset($post['category']) && $post['category'] != "" && $post['category'] != "0") {
            $query->andWhere(new \yii\db\Expression('FIND_IN_SET(:cat_to_find,merchant.category)'))
                    ->addParams([':cat_to_find' => $post['category']]);
        }
        if (isset($post['availability']) && $post['availability'] != "" && $post['availability'] == 1) {
            $query->andWhere(['merchant.availability' => 1]);
            // $having[] = "availability_exist > 0 ";
        }

        if ($having != NULL) {
            $query->having(implode(" AND ", $having));
        }


        return $query;
    }

    private function getDataByProximityStore($post, $query, $store_id) {
        $sql_distance = "merchant.id as merchant_id,`merchant`.*";
        $having = "";

        if (isset($post['latitude']) && $post['latitude'] != "" && isset($post['longitude']) && $post['longitude'] != "") {  // check lattitude and longitude is set.
            $latitude = $post['latitude'];
            $longitude = $post['longitude'];
            $radius = $post['radius'];
            $sql_distance .= ", (((acos(sin((" . $latitude . "*pi()/180)) * sin((`merchant`.`latitude`*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((`merchant`.`latitude`*pi()/180)) * cos(((" . $longitude . "-`merchant`.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance "; //Getting value based on given input location cordinates
            if (isset($post['radius']) && $post['radius'] != '') {
                $having = "(distance <= $radius) ";
            }
            $query->orderBy([
                'distance' => SORT_ASC,
            ]);
        }
        $query->select($sql_distance);
        if ($having != "") {
            $query->having($having);
        }
        $query->join('LEFT OUTER JOIN', 'products_services', 'products_services.merchant_id =merchant.id');
        if (isset($post['search_key']) && $post['search_key'] != "") {
            $query->andWhere(['LIKE', 'products_services.product_name_en', $post['search_key']]);
            $query->orWhere(['LIKE', 'products_services.search_tag', $post['search_key']]);
            $query->orWhere(['LIKE', 'merchant.business_name', $post['search_key']]);
            $query->orWhere(['LIKE', 'merchant.search_tag', $post['search_key']]);
        }
        if ($post['type_of_search'] == 2) {
            if (isset($post['city']) && $post['city'] != "" && $post['city'] != "0") {
                $query->andWhere(['merchant.city' => $post['city']]);
            }
            if (isset($post['area']) && $post['area'] != "") {
                $query->andWhere(['merchant.area' => $post['area']]);
            }
        }
        $query->andWhere(['merchant.status' => 10]);
        $query->andWhere(['merchant.franchise_id' => $store_id->id]);
        $query->groupBy('merchant.id');
        if (isset($post['category']) && $post['category'] != "" && $post['category'] != "0") {
            $query->andWhere(new \yii\db\Expression('FIND_IN_SET(:cat_to_find,merchant.category)'))
                    ->addParams([':cat_to_find' => $post['category']]);
        }
        return $query;
    }

    private function getDataByNameStore($post, $query, $store_id) {
// Geting Product list based on rating and availability
        $sql = "merchant.id as merchant_id,merchant.*";
        $having = [];
//        if (isset($post['latitude']) && $post['latitude'] != "" && isset($post['longitude']) && $post['longitude'] != "") {  // check lattitude and longitude is set.
//            $latitude = $post['latitude'];
//            $longitude = $post['longitude'];
//            if (isset($post['radius']) && $post['radius'] != '') {
//                $radius = $post['radius'];
//            } else {
//                $radius = "";
//            }
//
//            $sql .= " , (((acos(sin((" . $latitude . "*pi()/180)) * sin((`merchant`.`latitude`*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((`merchant`.`latitude`*pi()/180)) * cos(((" . $longitude . "-`merchant`.`longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance "; //Getting value based on given input location cordinates
//            if (isset($post['radius']) && $post['radius'] != '') {
//                $having[] = "(distance <= $radius) ";
//            }
//            $query->orderBy(['distance' => SORT_ASC]);
//        }
        $current_date = date('Y-m-d');
        $current_day = date("l", strtotime($current_date));
        if (isset($post['rating']) && $post['rating'] != "" && $post['rating'] != "0") {
            $sql .= ',(SELECT COUNT(*) FROM product_review WHERE product_review.review_for_id=merchant.id AND (product_review.review_type = 3)) as total_rating_count,(SELECT SUM(product_review.rating) FROM product_review WHERE product_review.review_for_id=merchant.id AND product_review.review_type = 3)/(SELECT COUNT(*) FROM product_review WHERE product_review.review_for_id = merchant.id AND product_review.review_type = 3) as average_rating';
            $having[] = "(total_rating_count = 0 OR average_rating >=  " . $post['rating'] . ")";
            $query->orderBy(['average_rating' => SORT_DESC]);
        }
//        if (isset($post['availability']) && $post['availability'] != "" && $post['availability'] == 1) {
//            $sql .= ',(SELECT COUNT(*) FROM week_days_availability WHERE week_days_availability.merchant_id = products_services.merchant_id AND week_days_availability.availability =1 AND (DATE(date) = "' . $current_date . '" OR day = "' . $current_day . '" ))  as availability_exist';
//        }
        $query->select($sql);
        $query->join('LEFT OUTER JOIN', 'products_services', 'products_services.merchant_id =merchant.id');
        // $query->join('LEFT OUTER JOIN', 'product_review', 'product_review.review_for_id =merchant.id AND (product_review.review_type =3)');
        if (isset($post['search_key']) && $post['search_key'] != "") {
            $query->andWhere(['LIKE', 'products_services.product_name_en', $post['search_key']]);
            $query->orWhere(['LIKE', 'products_services.search_tag', $post['search_key']]);
            $query->orWhere(['LIKE', 'merchant.business_name', $post['search_key']]);
            $query->orWhere(['LIKE', 'merchant.search_tag', $post['search_key']]);
        }
//        if (isset($post['city']) && $post['city'] != "") {
//            $query->andWhere(['merchant.city' => $post['city']]);
//        }
//        if (isset($post['area']) && $post['area'] != "") {
//            $query->andWhere(['merchant.area' => $post['area']]);
//        }
        $query->andWhere(['merchant.status' => 10]);
        $query->andWhere(['merchant.franchise_id' => $store_id->id]);
        if (isset($post['category']) && $post['category'] != "" && $post['category'] != "0") {
            $query->andWhere(new \yii\db\Expression('FIND_IN_SET(:cat_to_find,merchant.category)'))
                    ->addParams([':cat_to_find' => $post['category']]);
        }
        if (isset($post['availability']) && $post['availability'] != "" && $post['availability'] == 1) {
            $query->andWhere(['merchant.availability' => 1]);
            // $having[] = "availability_exist > 0 ";
        }
        $query->groupBy('merchant.id');
        if ($having != NULL) {
            $query->having(implode(" AND ", $having));
        }


        return $query;
    }

    private function addToSearchHistory($headers, $post = []) {
        if (isset($headers['lang']) && $headers['lang'] != "") {
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $errors = [];
        $userId = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']); //getting the user info using authtoken
        $store_id = \common\models\Franchise::find()->where(['access_token' => $headers['accessToken'], 'status' => 10])->one();

        $check_history_exist = \common\models\SearchHistory::find()->select('search_key,id,store,user_id,status')->where(['user_id' => $userId, 'store' => $store_id->id])->one();
        if ($check_history_exist != NULL) {

            $unser_datas = unserialize($check_history_exist->search_key);

            $result_data = [];
            if ($unser_datas != NULL) {
                $match_count = 0;
                foreach ($unser_datas as $unser_data) {
                    if ($post['type_of_search'] == $unser_data['type_of_search'] &&
                            $post['radius'] == $unser_data['radius'] &&
                            $post['category'] == $unser_data['category'] &&
                            $post['latitude'] == $unser_data['latitude'] &&
                            $post['longitude'] == $unser_data['longitude'] &&
                            (isset($unser_data['search_key']) && (isset($post['search_key']) && ($post['search_key'] == $unser_data['search_key']))) &&
                            $post['rating'] == $unser_data['rating'] &&
                            $post['availability'] == $unser_data['availability']
                    ) {
                        $match_count++;
                        $unser_data['count'] = $unser_data['count'] + 1;
                        $unser_data['updated_at'] = date("Y-m-d");
                    }
                    $result_data[] = $unser_data;
                }
                if ($match_count == 0) {
                    $search_params = $post;
                    $search_params['created_at'] = date("Y-m-d");
                    $search_params['updated_at'] = date("Y-m-d");
                    $search_params['count'] = 1;
                    $result_data[] = $search_params;
                }
                $check_history_exist->search_key = serialize($result_data);

                if ($check_history_exist->save()) {

                } else {

                    $errors[] = $check_history_exist->errors;
                }
            }
        } else {
            $search_items = [];
            $search_params = $post;
            $search_params['created_at'] = date("Y-m-d");
            $search_params['updated_at'] = date("Y-m-d");
            $search_params['count'] = 1;
            array_push($search_items, $search_params);
            $ser_data = serialize($search_items);
            $seach_history_model = new \common\models\SearchHistory();
            $seach_history_model->id = uniqid('AGOGO');
            $seach_history_model->user_id = $userId;
            $seach_history_model->store = $store_id->id;
            $seach_history_model->search_key = $ser_data;
            $seach_history_model->status = 1;
            if ($seach_history_model->save()) {

            } else {
                $errors[] = $seach_history_model->errors;
            }
        }
        $return ['error'] = $errors;
        return $return;
    }

}
