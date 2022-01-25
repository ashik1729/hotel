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
use kartik\mpdf\Pdf;

/**
 * Site controller
 */
class InvoiceController extends Controller {

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
        $action_list = ['GET', "POST"];
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
                                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $data, 'events');
                                    \Yii::$app->response->data = $array;
                                } else if ($data['data'] != NULL) {
                                    $transaction->commit();
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data['data'], 'events');
                                } else {
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, $data['data'], 'events');
                                }
                            } else {
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(415, $name, $lang, $post, (object) [], 'events');
                            }
                        } else {

                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(443, $name, $lang, $post, (object) [], 'events');
                        }
                    } else {
                        \Yii::$app->response->statusCode = 401;

                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'events');
                    }
                } else {
                    \Yii::$app->response->statusCode = 401;

                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(401, $name, $lang, $post, (object) [], 'events');
                }
            } else {

                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'events');
            }
        } else {

            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'events');
        }
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
        $errors = [];

        $params = ['order_id', 'merchant_id'];
        // Result base on store ID

        if ($params != NULL) {
            foreach ($params as $param) {
                if (isset($post[$param])) {
                    if ($post[$param] == NULL || $post[$param] == "") {  // Checking all Get Params are filled if ID not there
                        $errors[$param] = $post[$param]; // Creating Error instence
                    }
                } else {
                    $errors[$param] = NULL;
                }
            }
        }

        $order = \common\models\Orders::findOne(['id' => $post['order_id']]);
        if ($order != NULL) {
            $query = \common\models\OrderInvoice::find()->where(['order_id' => $order->id, 'merchant_id' => $post['merchant_id']]); // Building Order Query
            $model = $query->one();
            if ($model != NULL) {
                $file = $this->saveInvoice($model->order_id, $model->merchant_id);
                $return = [
                    'invoice' => $model->invoice,
                    'invoice_date' => $model->invoice_date,
                    'invoice_file' => $file ? "uploads/temp_invoice/" . $file : "Invoice Not Available Yet",
                ];
            }
        }

        $finaldata['error'] = $errors;
        $finaldata['data'] = $return;
        return $finaldata;
    }

    private function saveInvoice($order_id, $merchant_id) {
        $order = \common\models\Orders::findOne(['id' => $order_id]);
        $order_productquery = \common\models\OrderProducts::find()->select('merchant_id')->where(['order_id' => $order->id]);

        $order_productquery->andWhere(['merchant_id' => $merchant_id]);
        $order_products = $order_productquery->asArray()->all();
        $merchant_lists = array_unique(array_column($order_products, 'merchant_id'));

        $content = $this->renderPartial('_invoice', ['order' => $order, 'merchant_list' => $merchant_lists]);
//            print_r($content);
//            exit;
        $file_name = $order_id . '_' . md5(microtime()) . '.pdf';
        $path = \yii::$app->basePath . '/../uploads/temp_invoice/' . date('Y_m_d') . '/' . $file_name;
        $file_path = date('Y_m_d') . '/' . $file_name;
        $download_url = \yii::$app->request->baseUrl . '/../uploads/temp_invoice/' . date('Y_m_d') . '/' . $file_name;
        $targetFolder = \yii::$app->basePath . '/../uploads/temp_invoice/' . date('Y_m_d') . '/';
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            'filename' => $path,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_FILE,
//            'marginTop' => 0,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => ''],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader' => [''],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);
        $pdf->render();
        return $file_path;
    }

    private function getAddress($id, $lang) {
        $query = \common\models\UserAddress::find()->where(['id' => $id]); // Check the review is approved by admin
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
                        "product_name" => $model->product ? ($lang == 1 ? $model->product->product_name_en : $model->product->product_name_en) : "NA",
                        "merchant_id" => $model->merchant_id,
                        "quantity" => $model->quantity,
                        "amount" => Yii::$app->Currency->convert($model->amount, $order->store, $lang),
                        "order_status" => $model->status != 0 ? ($lang == 1 ? ($model->status ? $model->orderStatus->name : $model->orderStatus->name_ar) : "NA") : "NA",
                        "options" => $this->getOptions($model, $lang),
                        "delivery_date" => $model->date,
                        "delivery_date" => $model->booking_slot,
                        "invoice" => $this->getInvoice($model->order_id, $model->product->merchant_id),
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

}
