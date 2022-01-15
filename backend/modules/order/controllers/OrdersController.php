<?php

namespace backend\modules\order\controllers;

use Yii;
use common\models\Orders;
use common\models\OrdersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'Orders';
        $get_rules_list = \common\models\AdminRoleList::find()->where(['controller' => $tbl_name . 'Controller'])->all();
        $get_rules = [];
        $route = strtolower(preg_replace('~(?=[A-Z])(?!\A)~', '-', $tbl_name));
        $rule_list = [];
        $action[] = "error";

        if ($get_rules_list != NULL) {
            foreach ($get_rules_list as $get_rules_li) {
                $get_rules = \common\models\AdminRoleLocation::find()->where(['role_id' => Yii::$app->user->identity->role, 'role_list_id' => $get_rules_li->id])->all();
                if ($get_rules != NULL) {
                    foreach ($get_rules as $get_rule) {
                        $action[] = $get_rule->location->action;
                    }
                }
            }
        }
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => $action,
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['POST'],
                ],
            ],
        ];
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function init() {
        parent::init();
        if (Yii::$app->user->isGuest) {
            return $this->redirect(yii::$app->request->baseUrl . '/site/login');
        }
    }

    /**
     * Lists all Orders models.
     * @return mixed
     */
    public function actionAddOrderProducts($id) {
        $model = new \common\models\OrderProducts();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        if ($request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                $qty = $model->quantity;
                $option_array = [];
                if (isset($_POST['OrderProducts']['attribute'])) {
                    foreach ($_POST['OrderProducts']['attribute'] as $key => $value) {
                        $option_array[] = $value;
                    }
                }
                sort($option_array);
                $imp_option = implode(',', $option_array);
                $order = Orders::find($id)->one();
                $product = \common\models\ProductsServices::findOne(['id' => $model->product_id]);
                $check_product_exist_query = \common\models\OrderProducts::find()->where(['order_id' => $id, 'user_id' => $order->user_id, 'product_id' => $model->product_id]);
                if ($imp_option != "") {
                    $check_product_exist_query->andWhere(['options' => $imp_option]);
                }
                if ($product != NULL) {
                    if ($product->type == 2 || $product->type == 3) {
                        if (isset($_POST['OrderProducts']['date'])) {
                            $check_product_exist_query->andWhere(['date' => $_POST['OrderProducts']['date']]);

                            if (isset($_POST['OrderProducts']['booking_slot'])) {
                                $check_product_exist_query->andWhere(['booking_slot' => $_POST['OrderProducts']['booking_slot']]);
                            }
                        }
                    }
                }
                $check_product_exist = $check_product_exist_query->one();
                if ($check_product_exist != NULL) {

                    $model = $check_product_exist;
                    $model->quantity = $qty + $check_product_exist->quantity;
                }
                $model->user_id = $order->user_id;
                $model->booking_slot = $_POST['OrderProducts']['booking_slot'];
                $model->merchant_id = $product->merchant_id;
                $model->options = implode(',', $option_array);
                $model->date = $_POST['OrderProducts']['date'];
                $model->amount = Yii::$app->Products->price($product);
                $model->status = 1;   // 0- Abonded/Deleted, 1- Pending, 2-ORder Placed, 3- Shipped, 4-Delivered, 5- Returned , 6-Cancelled
                $model->created_by = yii::$app->user->identity->id;
                $model->updated_by = yii::$app->user->identity->id;
                $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                $model->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                if ($model->save()) {
                    $order->total_amount = $this->calculateOrderAmount($order->id);
                    if ($order->save()) {
                        $array['status'] = 200;
                        $array['error'] = '';
                        $array['message'] = 'Success.';
                    } else {
                        $array['status'] = 201;
                        $array['error'] = $order->errors;
                        $array['message'] = 'Error.';
                    }
                } else {
                    $array['status'] = 201;
                    $array['error'] = $model->errors;
                    $array['message'] = 'Error.';
                }

                echo json_encode($array);
                exit;
            }
        }
    }

    /**
     * Add Product to temp cart
     * @return mixed
     */
    public function actionDeleteCart($session_id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $array = ["status" => 201, "error" => "", "message" => "Not Saved"];
        if ($request->isAjax) {
            if (($model = \common\models\Cart::findOne(['id' => $_POST['cart_id'], 'session_id' => $session_id])) !== null) {
                if ($model->delete()) {
                    $array['status'] = 200;
                    $array['error'] = '';
                    $array['message'] = 'Success.';
                } else {
                    $array['status'] = 201;
                    $array['error'] = $model->errors;
                    $array['message'] = 'Error.';
                }
            } else {
                $array['message'] = 'Cart NOt Found';
            }
        } else {
            $array['message'] = 'Invalid Request';
        }
        echo json_encode($array);
        exit;
    }

    public function actionAddToCart($session_id) {
        $model = new \common\models\Cart();
        $model->scenario = "admin_create_cart";
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        if ($request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                $qty = $model->quantity;
                $option_array = [];
                if (isset($_POST['OrderProducts']['attribute'])) {
                    foreach ($_POST['OrderProducts']['attribute'] as $key => $value) {
                        $option_array[] = $value;
                    }
                }
                sort($option_array);
                $imp_option = implode(',', $option_array);
                $product = \common\models\ProductsServices::findOne(['id' => $model->product_id]);
                $check_product_exist_query = \common\models\Cart::find()->where(['session_id' => $session_id, 'product_id' => $model->product_id]);
                if ($imp_option != "") {
                    $check_product_exist_query->andWhere(['options' => $imp_option]);
                }
                if ($product != NULL) {
                    if ($product->type == 2 || $product->type == 3) {
                        if (isset($_POST['OrderProducts']['date'])) {
                            $check_product_exist_query->andWhere(['date' => $_POST['OrderProducts']['date']]);
                            $model->date = $_POST['OrderProducts']['date'];
                            if (isset($_POST['OrderProducts']['booking_slot'])) {
                                $check_product_exist_query->andWhere(['booking_slot' => $_POST['OrderProducts']['booking_slot']]);
                                $model->booking_slot = $_POST['OrderProducts']['booking_slot'];
                            }
                        }
                    }
                }
                $check_product_exist = $check_product_exist_query->one();
                if ($check_product_exist != NULL) {

                    $model = $check_product_exist;
                    $model->quantity = $qty + $check_product_exist->quantity;
                }
                if (isset($session_id) && $session_id != "") {
                    $model->session_id = $session_id;
                } else {
                    $session_id = md5(time());
                    Yii::$app->session->set('cart_session', $session_id);
                    $model->session_id = $session_id;
                }
                if (isset($_POST['OrderProducts']['booking_slot'])) {
                    $model->booking_slot = $_POST['OrderProducts']['booking_slot'];
                }
                $model->id = uniqid('AGOGO');
                $model->options = implode(',', $option_array);
                $model->status = 1;   // 0- Abonded/Deleted, 1- Pending, 2-ORder Placed, 3- Shipped, 4-Delivered, 5- Returned , 6-Cancelled
                $model->created_by = yii::$app->user->identity->id;
                $model->updated_by = yii::$app->user->identity->id;
                $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                $model->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                if ($model->save()) {
                    $array['status'] = 200;
                    $array['error'] = '';
                    $array['message'] = 'Success.';
                } else {
                    $array['status'] = 201;
                    $array['error'] = $model->errors;
                    $array['message'] = 'Error.';
                }

                echo json_encode($array);
                exit;
            }
        }
    }

    public function actionUpdateOrderProducts($id) {
        $model = \common\models\OrderProducts::findOne(['id' => $id]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $array = [];
        $request = Yii::$app->request;
        if ($request->isAjax) {
            if ($model != NULL) {
                $order = Orders::find($model->order_id)->one();
                if ($order != NULL) {
                    if ($model->load(Yii::$app->request->post())) {
                        $qty = $model->quantity;
//                        $check_product_exist_query = \common\models\OrderProducts::find()->where(['order_id' => $model->order_id, 'user_id' => $order->user_id, 'product_id' => $model->product_id]);
//                        $option_array = [];
//                        if (isset($_POST['OrderProducts']['attribute'])) {
//                            foreach ($_POST['OrderProducts']['attribute'] as $key => $value) {
//                                $option_array[] = $value;
//                            }
//                            sort($option_array);
//                            $imp_option = implode(',', $option_array);
//                            if ($imp_option != "") {
//                                $check_product_exist_query->andWhere(['options' => $imp_option]);
//                            }
//                        }
                        $product = \common\models\ProductsServices::findOne(['id' => $model->product_id]);
//                        if ($product != NULL) {
//                            if ($product->type == 2 || $product->type == 3) {
//                                if (isset($_POST['OrderProducts']['date'])) {
//                                    $check_product_exist_query->andWhere(['date' => $_POST['OrderProducts']['date']]);
//
//                                    if (isset($_POST['OrderProducts']['booking_slot'])) {
//                                        $check_product_exist_query->andWhere(['booking_slot' => $_POST['OrderProducts']['booking_slot']]);
//                                    }
//                                }
//                            }
//                        }
//                        $check_product_exist = $check_product_exist_query->one();
//                        if ($check_product_exist != NULL) {
//                            $model = $check_product_exist;
//                        }
                        $model->quantity = $qty;
                        $model->user_id = $order->user_id;
                        $model->booking_slot = $_POST['OrderProducts']['booking_slot'];
                        $model->merchant_id = $product->merchant_id;
//                        $model->options = implode(',', $option_array);
                        $model->date = $_POST['OrderProducts']['date'];
                        $model->amount = Yii::$app->Products->price($product);
                        $model->status = 1;   // 0- Abonded/Deleted, 1- Pending, 2-ORder Placed, 3- Shipped, 4-Delivered, 5- Returned , 6-Cancelled
                        $model->updated_by = yii::$app->user->identity->id;
                        $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                        if ($model->save()) {
                            $order->total_amount = $this->calculateOrderAmount($order->id);
                            if ($order->save()) {
                                $array['status'] = 200;
                                $array['error'] = '';
                                $array['message'] = 'Success.';
                            } else {
                                $array['status'] = 201;
                                $array['error'] = $order->errors;
                                $array['message'] = 'Error.';
                            }
                        } else {
                            $array['status'] = 201;
                            $array['error'] = $model->errors;
                            $array['message'] = 'Error.';
                        }
                    }
                }
            }
        }
        echo json_encode($array);
        exit;
    }

    public function actionUpdateCartItem($id) {
        $model = \common\models\Cart::findOne(['id' => $id]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $array = [];
        $request = Yii::$app->request;
        if ($request->isAjax) {
            if ($model != NULL) {
                if ($model->load(Yii::$app->request->post())) {
                    $qty = $model->quantity;
                    $model->quantity = $qty;
                    $model->booking_slot = $_POST['OrderProducts']['booking_slot'];
                    $model->date = $_POST['Cart']['date'];
                    $model->status = 1;   // 0- Abonded/Deleted, 1- Pending, 2-ORder Placed, 3- Shipped, 4-Delivered, 5- Returned , 6-Cancelled
                    $model->updated_by = yii::$app->user->identity->id;
                    $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    $option_array = [];
                    if (isset($_POST['OrderProducts']['attribute'])) {
                        foreach ($_POST['OrderProducts']['attribute'] as $key => $value) {
                            $option_array[] = $value;
                        }
                    }
                    sort($option_array);
                    $model->options = implode(',', $option_array);

                    if ($model->save()) {
                        $array['status'] = 200;
                        $array['error'] = '';
                        $array['message'] = 'Success.';
                    } else {
                        $array['status'] = 201;
                        $array['error'] = $model->errors;
                        $array['message'] = 'Error.';
                    }
                }
            }
        }
        echo json_encode($array);
        exit;
    }

    public function actionDeleteOrderProducts($id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $array = [];
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            $model = \common\models\OrderProducts::findOne(['id' => $id]);
            if ($model != NULL) {
                $order = Orders::find($model->order_id)->one();
                if ($order != NULL) {
                    if (\common\models\OrderHistory::deleteAll(['AND', 'order_product_id = :order_product_id',], [':order_product_id' => $id])) {
                        if ($model->delete()) {
                            $order->total_amount = $this->calculateOrderAmount($order->id);
                            if ($order->save()) {
                                $transaction->commit();
                                $array['status'] = 200;
                                $array['error'] = '';
                                $array['message'] = 'Success.';
                            } else {
                                $transaction->rollBack();

                                $array['status'] = 201;
                                $array['error'] = $order->errors;
                                $array['message'] = 'Error.';
                            }
                        } else {
                            $transaction->rollBack();

                            $array['status'] = 201;
                            $array['error'] = $model->errors;
                            $array['message'] = 'Error.';
                        }
                    } else {
                        $transaction->rollBack();

                        $array['status'] = 201;
                        $array['error'] = $model->errors;
                        $array['message'] = 'Error.';
                    }
                }
            }
        }
        echo json_encode($array);
        exit;
    }

    public function actionUpdateOrderDetails($id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;

        if ($request->isAjax) {
            $order = Orders::findOne(['id' => $id]);
            if ($order != NULL) {
                if ($order->load(Yii::$app->request->post())) {
                    if ($order->save()) {
                        $array['status'] = 200;
                        $array['error'] = '';
                        $array['message'] = 'Success.';
                    } else {
                        $array['status'] = 201;
                        $array['error'] = $order->errors;
                        $array['message'] = 'Error.';
                    }
                } else {
                    $array['status'] = 201;
                    $array['error'] = "Order Not Found";
                    $array['message'] = 'Error.';
                }
                echo json_encode($array);
                exit;
            }
        }
    }

    public function actionUpdateUserAddress($id, $type) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;

        if ($request->isAjax) {
            $order = Orders::findOne(['id' => $id]);
            if ($order != NULL) {
                $oldmodel = \common\models\UserAddress::find()->where(['id' => $_POST['UserAddress']['id']])->one();
                if ($order->ship_address != $order->bill_address) {
                    if ($oldmodel != NULL) {
                        $model = $oldmodel;
                    } else {
                        $model = new \common\models\UserAddress();
                    }
                } else {
                    $model = new \common\models\UserAddress();
                }
                if ($model->load(Yii::$app->request->post())) {
                    $model->user_id = $order->user_id;
                    $model->default_billing_address = 0;
                    $model->default_shipping_address = 0;
                    $model->created_by = yii::$app->user->identity->id;
                    $model->updated_by = yii::$app->user->identity->id;
                    $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    $model->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    if ($model->save()) {
                        if ($type == 1) {
                            $order->ship_address = $model->id;
                        } else if ($type == 2) {
                            $order->bill_address = $model->id;
                        }
                        if ($order->save()) {
                            $array['status'] = 200;
                            $array['error'] = '';
                            $array['message'] = 'Success.';
                        } else {
                            $array['status'] = 201;
                            $array['error'] = $order->errors;
                            $array['message'] = 'Error.';
                        }
                    } else {
                        $array['status'] = 201;
                        $array['error'] = $model->errors;
                        $array['message'] = 'Error.';
                    }
                } else {
                    $array['status'] = 201;
                    $array['error'] = "Order Not Found";
                    $array['message'] = 'Error.';
                }
                echo json_encode($array);
                exit;
            }
        }
    }

    public function actionAddOrderHistory($id) {
        $model = new \common\models\OrderHistory();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        if ($request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {

                $order = Orders::find($id)->one();
                $orderproduct = \common\models\OrderProducts::find()->where(['id' => $model->order_product_id, 'order_id' => $id])->one();
                if ($orderproduct != NULL) {
                    $orderproduct->status = $_POST['OrderHistory']['order_status'];
                    $model->order_id = $id;
                    $model->order_product_id = $_POST['OrderHistory']['order_product_id'];
                    $model->status = 1;   // 0- Abonded/Deleted, 1- Pending, 2-ORder Placed, 3- Shipped, 4-Delivered, 5- Returned , 6-Cancelled
                    $model->created_by = yii::$app->user->identity->id;
                    $model->updated_by = yii::$app->user->identity->id;
                    $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    $model->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    if ($model->save()) {

                        if ($orderproduct->save()) {
                            if ($orderproduct->status == 7 || $orderproduct->status == 8) {
                                $template_key["{%order_item%}"] = $orderproduct->product->product_name_en;
                                $template_key["{%order_item_ar%}"] = $orderproduct->product->product_name_ar;
                                $titleEn = $this->getMessage("order_reception_title", 1);
                                $titleAr = $this->getMessage("order_reception_title", 2);
                                $bodyEn = $this->getBody("order_reception_description", $template_key, 1);
                                $bodyAr = $this->getBody("order_reception_description", $template_key, 2);
                                $notif_key['type'] = 4;
                                $notif_key['product_id'] = strval($orderproduct->product_id);
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
                                    "reciever" => [$orderproduct->user_id],
                                ];
                                $saveNotifications = Yii::$app->NotificationManager->savenotifications($data);
                                $result = Yii::$app->NotificationManager->pushnotification($orderproduct->user_id, $titleEn, $titleAr, $bodyEn, $bodyAr, $notif_key);
                            }
                            $array['status'] = 200;
                            $array['error'] = '';
                            $array['notificationData'] = $data;
                            $array['message'] = 'Success.';
                        } else {
                            $array['status'] = 201;
                            $array['error'] = $order->errors;
                            $array['message'] = 'Error.';
                        }
                    } else {
                        $array['status'] = 202;
                        $array['error'] = $model->errors;
                        $array['message'] = 'Error.';
                    }
                } else {
                    $array['status'] = 203;
                    $array['error'] = "Order Product not available";
                    $array['message'] = 'Error.';
                }

                echo json_encode($array);
                exit;
            }
        }
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

    public function actionLoadOrderProduct() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $order = Orders::findOne(['id' => $_POST['order_id']]);

            if ($order != NULL) {
                $order_products = \common\models\OrderProducts::find()->where(['order_id' => $_POST['order_id'], 'id' => $_POST['order_product_id']])->one();
                if ($order_products != NULL) {
                    $attributes = $this->getProductAttributes($order_products->product_id);
                    $content = $this->renderPartial('_order_product_edit_content', ['model' => $order_products, 'attributes' => $attributes, 'url' => 'update-order-products?id=' . $order_products->id]);

                    $array['status'] = 200;
                    $array['error'] = '';
                    $array['message'] = $content;
                } else {
                    $array['status'] = 201;
                    $array['error'] = "";
                    $array['message'] = 'Error.';
                }
            } else {
                $array['status'] = 201;
                $array['error'] = "Order Not Found";
                $array['message'] = 'Error.';
            }

            echo json_encode($array);
            exit;
        }
    }

    public function actionLoadCartItem() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $cart = \common\models\Cart::findOne(['id' => $_POST['cart_id']]);

            if ($cart != NULL) {
                $attributes = $this->getProductAttributes($cart->product_id);
                $content = $this->renderPartial('_order_product_edit_content', ['model' => $cart, 'attributes' => $attributes, 'url' => 'update-cart-item?id=' . $cart->id]);

                $array['status'] = 200;
                $array['error'] = '';
                $array['attribute'] = $attributes;
                $array['message'] = $content;
            } else {
                $array['status'] = 201;
                $array['error'] = "Order Not Found";
                $array['message'] = 'Error.';
            }

            echo json_encode($array);
            exit;
        }
    }

    public function actionDownloadInvoice() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        if ($request->isAjax) {
            $order = Orders::findOne(['id' => $_POST['order_id']]);
            $order_productquery = \common\models\OrderProducts::find()->select('merchant_id')->where(['order_id' => $order->id]);
            if ($_POST['merchant_id'] != 0) {
                $order_productquery->andWhere(['merchant_id' => $_POST['merchant_id']]);
            }
            $order_products = $order_productquery->asArray()->all();
            $merchant_lists = array_unique(array_column($order_products, 'merchant_id'));

            $content = $this->renderPartial('_invoice', ['order' => $order, 'merchant_list' => $merchant_lists]);
//            print_r($content);
//            exit;
            $file_name = $_POST['order_id'] . '_' . md5(microtime()) . '.pdf';
            $path = \yii::$app->basePath . '/../uploads/temp_invoice/' . date('Y_m_d') . '/' . $file_name;
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

            // return the pdf output as per the destination setting
            $pdf->render();
            $array['status'] = 200;
            $array['error'] = '';
            $array['message'] = $download_url;
            echo json_encode($array);
            exit;
        }
    }

    public function actionDownloadDeliverySlip($id) {
        $order_id = base64_decode($id);
        $order = Orders::findOne(['id' => $order_id]);
        if ($order != NULL) {
            $content = $this->renderPartial('_delivery_slip', ['order' => $order]);

            $file_name = $_POST['order_id'] . '_delivery_slip_' . md5(microtime()) . '.pdf';
            $pdf = new Pdf([
                // set to use core fonts only
                'mode' => Pdf::MODE_CORE,
                // A4 paper format
                'format' => Pdf::FORMAT_A4,
                'filename' => $file_name,
                // portrait orientation
                'orientation' => Pdf::ORIENT_PORTRAIT,
                // stream to browser inline
                'destination' => Pdf::DEST_DOWNLOAD,
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

            // return the pdf output as per the destination setting
            return $pdf->render();
        } else {
            echo "No Order Found";
            exit;
        }
    }

    private function saveInvoice($order_id, $merchant_id) {
        $order = Orders::findOne(['id' => $order_id]);
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

    public function actionGenerateInvoice() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        if ($request->isAjax) {
            $id = $_POST['order_id'];
            $merchant_id = $_POST['merchant_id'];
            $merchant = \common\models\Merchant::findOne(['id' => $merchant_id]);
            $orderinvoice = \common\models\OrderInvoice::find()->where(['order_id' => $id, 'merchant_id' => $merchant_id])->one();

            if ($orderinvoice != NULL) {
                $neworderinvoice = $orderinvoice;
                $neworderinvoice->invoice = ($merchant->franchise->invoice_prefix ? $merchant->franchise->invoice_prefix : "") . ($merchant->invoice_prefix ? $merchant->invoice_prefix : '') . 'M' . $merchant->id . 'O' . ($id + 2000);
                $neworderinvoice->invoice_date = date('Y-m-d H:i:s');
            } else {
                $neworderinvoice = new \common\models\OrderInvoice();
                $neworderinvoice->invoice = ($merchant->franchise->invoice_prefix ? $merchant->franchise->invoice_prefix : "") . ($merchant->invoice_prefix ? $merchant->invoice_prefix : '') . 'M' . $merchant->id . 'O' . ($id + 2000);
                $neworderinvoice->invoice_date = date('Y-m-d H:i:s');
                $neworderinvoice->order_id = $id;
                $neworderinvoice->merchant_id = $merchant_id;
                $neworderinvoice->created_by = yii::$app->user->identity->id;
                $neworderinvoice->updated_by = yii::$app->user->identity->id;
                $neworderinvoice->updated_by_type = 2;
                $neworderinvoice->created_by_type = 2;
            }
            if ($neworderinvoice->save()) {
                $file = $this->saveInvoice($neworderinvoice->order_id, $neworderinvoice->merchant_id);

                if ($file != "") {
                    $neworderinvoice->invoice_file = $file;
                    $neworderinvoice->save(FALSE);
                }
                $array['status'] = 200;
                $array['error'] = '';
                $array['message'] = 'Success.';
            } else {
                $array['status'] = 202;
                $array['error'] = $neworderinvoice->errors;
                $array['message'] = 'Error.';
            }
            echo json_encode($array);
            exit;
        }
    }

    public function calculateOrderAmount($order_id) { // Calculate Order Amount
        $total_amount = 0;
        if ($order_id != 0) {
            $get_order_products = \common\models\OrderProducts::find()->where(['order_id' => $order_id])->all();
            foreach ($get_order_products as $get_order) {
                $total_amount += ($get_order->quantity * Yii::$app->Products->price($get_order->product));
            }
        }
        return $total_amount;
    }

    public function actionIndex() {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        $searchModel = new \common\models\OrderProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        $order_products = new \common\models\OrderProducts();
        $orderhistorymodel = new \common\models\OrderHistory();
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'order_products' => $order_products,
                    'orderhistorymodel' => $orderhistorymodel,
        ]);
    }

    public function actionGetOrderProductHistory() {
        $request = Yii::$app->request;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($request->isAjax) {

            $order_product_id = $_POST['order_product_id'];
            $order_products = \common\models\OrderProducts::findOne(['id' => $order_product_id]);
            $get_data = \common\models\OrderHistory::find()->where(['order_id' => $order_products->order_id, 'order_product_id' => $order_products->id])->orderBy('created_at DESC ')->all();
            $data = '';
            if ($get_data != NULL) {
                $data = $this->renderPartial('_order_history', [
                    'models' => $get_data,
                ]);
            }

            $array['status'] = 200;
            $array['error'] = '';
            $array['message']['data'] = $data;
            echo json_encode($array);
            exit;
        }
    }

    public function actionGetUserInfo() {
        $request = Yii::$app->request;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($request->isAjax) {

            $user_id = $_POST['user_id'];
            $get_user = \common\models\User::findOne(['id' => $user_id]);
            $data = [];
            if ($get_user != NULL) {
                $data = [
                    "first_name" => $get_user->first_name,
                    "last_name" => $get_user->last_name,
                    "email" => $get_user->email,
                    "mobile_number" => $get_user->mobile_number
                ];
            }

            $array['status'] = 200;
            $array['error'] = '';
            $array['message']['data'] = $data;
            echo json_encode($array);
            exit;
        }
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Orders();
        $model->scenario = "create_order_backend";
        $usermodel = new \common\models\User();
        $usermodel->scenario = "create_order_backend";
        $cart_items = new \common\models\Cart();
        $addressmodel = new \common\models\UserAddress();
        $session = Yii::$app->session;
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $get_cart = \common\models\Cart::find()->where(['session_id' => $session->get('cart_session')])->asArray()->all();
            if ($get_cart != NULL) {
                $cart_ids = array_column($get_cart, 'id');
                if (isset($_POST['Orders']['user_id']) && $_POST['Orders']['user_id'] != "") {
                    $user_id = $_POST['Orders']['user_id'];
                    $model->user_id = $user_id;
                } else {
                    if ($usermodel->load(Yii::$app->request->post())) {
                        $user = $this->addUser($usermodel, $_POST['user']);
                        if ($user->errors == NULL) {
                            $model->user_id = $user->id;
                        }
                    }
                }
                $addressmodel->load(Yii::$app->request->post());
                if (isset($_POST['UserAddress']['shipping_address'])) {
                    $shipp_address = $this->addAddress($_POST['UserAddress']['shipping_address'], 'ship', $model->user_id);
                    if ($shipp_address != NULL) {
                        $model->ship_address = $shipp_address->id;
                    } else {
                        $addressmodel->addError('first_name', 'Shipp Address Not Filled');
                    }
                }

                if (isset($_POST['UserAddress']['billing_address'])) {
                    $bill_address = $this->addAddress($_POST['UserAddress']['billing_address'], 'bill', $model->user_id);
                    if ($bill_address != NULL) {
                        $model->bill_address = $bill_address->id;
                    } else {
                        $addressmodel->addError('first_name', 'Billing Address Not Filled');
                    }
                }
                $model->store = $_POST['Orders']['store'];
                $model->created_by = yii::$app->user->identity->id;
                $model->updated_by = yii::$app->user->identity->id;
                $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                $model->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                $model->shipping_charge = $this->getShippingCharge($cart_ids);
                $model->total_amount = $this->calculateOrderAmountByCart($cart_ids) + $model->shipping_charge;
                if ($model->save()) {
                    $order_product = $this->orderProducts($model, $cart_ids);
                    $shippping = $this->orderShipping($model);
                    if ($order_product == NULL || $shippping == NULL) {

                        $order_payment = new \common\models\OrderPayments();
                        $order_payment->order_id = $model->id;
                        $order_payment->pay_amount = $model->total_amount;
                        $order_payment->pay_type = $model->payment_method;
                        $order_payment->transaction_id = $model->transaction_id;
                        $order_payment->comment = "";
                        $order_payment->payment_status = 1;
                        $order_payment->status = 1;
                        $order_payment->created_by = $model->user_id;
                        $order_payment->updated_by = $model->user_id;
                        $order_payment->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                        $order_payment->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                        if ($order_payment->save()) {
                            if (Yii::$app->ManageRequest->getVariable('environment') == "S" || Yii::$app->ManageRequest->getVariable('environment') == "P") { // Project is on staging or in Prouction
                                Yii::$app->MailRequest->sendUserOrderConfirmation($model, 1); // Sending Account create confirmation mail to user
                            }
                            if ($session->get('cart_session')) {
                                if (\common\models\Cart::deleteAll(['AND', 'session_id = :session_id'], [':session_id' => $session->get('cart_session')])) {

                                    $session->remove('cart_session');
                                }
                            }
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        } else {
                            $transaction->rollBack();
                        }
                    } else {
                        $transaction->rollBack();
                    }
                }
            } else {
                $model->addError('user_id', 'Add atleast one item into cart');
            }
        }
        if ($model->errors == NULL) {
            if (!Yii::$app->request->isAjax) {
                if ($session->get('cart_session')) {
                    if (\common\models\Cart::deleteAll(['AND', 'session_id = :session_id', []], [':session_id' => $session->get('cart_session')])) {
                        $session->remove('cart_session');
                    }
                }
                $session_id = md5(time());
                Yii::$app->session->set('cart_session', $session_id);
            }
        }

        return $this->render('create', [
                    'model' => $model,
                    'usermodel' => $usermodel,
                    'cart_items' => $cart_items,
                    'addressmodel' => $addressmodel,
        ]);
    }

    public function addAddress($data, $type, $user_id) { // Calculate Order Amount
        $model = new \common\models\UserAddress();
        $model->attributes = $data;
        $model->user_id = $user_id;
        if ($type == 'ship') {
            $model->default_shipping_address = 1;
            $model->default_billing_address = 0;
        }
        if ($type == 'bill') {
            $model->default_shipping_address = 0;
            $model->default_billing_address = 1;
        }
        $model->created_by = yii::$app->user->identity->id;
        $model->updated_by = yii::$app->user->identity->id;
        $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
        $model->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
        if ($model->save(false)) {
            return $model;
        }
    }

    public function addUser($model, $post) { // Calculate Order Amount
        if ($model != NULL) {
            $check_user_exist = \common\models\User::find()->where(['email' => $post['email']])->andWhere('AND email IS NOT NULL')->one();
            if ($check_user_exist != NULL) {
                return $check_user_exist;
            } else {
                $model->first_name = $post['first_name'];
                $model->last_name = $post['last_name'];
                $model->mobile_number = $post['mobile_number'];
                $model->email = $post['email'];
                $model->status = 10;
                $model->account_type = 1;
                $model->user_type = 1;
                $model->created_by = yii::$app->user->identity->id;
                $model->created_by = yii::$app->user->identity->id;
                $model->created_by = yii::$app->user->identity->id;
                $model->updated_by = yii::$app->user->identity->id;
                $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                $model->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                if ($model->save(false)) {
                    return $model;
                }
            }
        }
    }

    public function calculateOrderAmountByCart($cart_id) { // Calculate Order Amount
        $total_amount = 0;
        if ($cart_id != NULL) {
            foreach ($cart_id as $carts) {
                $cart = \common\models\Cart::findOne(['id' => $carts]);
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
                $cart = \common\models\Cart::findOne(['id' => $carts]);
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
                    $orderProducts->status = 2;   // 0- Abonded/Deleted, 1- Pending, 2-ORder Placed, 3- Shipped, 4-Delivered, 5- Returned , 6-Cancelled
                    $orderProducts->created_by = $order->user_id;
                    $orderProducts->updated_by = $order->user_id;
                    $orderProducts->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    $orderProducts->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
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
                $orderShipping->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                $orderShipping->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
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
        $order_history->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
        $order_history->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
        if ($order_history->save()) {

        } else {
            $order_history_error[] = $order_history->errors;
        }
        return $order_history_error;
    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function getProductAttributes($product_id) {
        $attributes = [];
        $product = \common\models\ProductsServices::find()->where(['id' => $product_id])->one();
        if ($product != NULL) {
            $get_attributes = \common\models\ProductAttributesValue::find()
                            ->select("product_attributes_value.id,attributes_value_id,price,attributes_value.value as attributes_value,attributes.name as name,attributes.id as attributes_id")
                            ->where(['product_attributes_value.status' => 1, 'product_attributes_value.product_id' => $product_id])
                            ->innerJoinWith('attributesValue', false)
                            ->join('LEFT OUTER JOIN', 'attributes', 'attributes_value.attributes_id =attributes.id')
                            ->orderBy(['product_attributes_value.sort_order' => SORT_ASC])
                            ->asArray()->all();
            $attributes_lists = array_column($get_attributes, 'attributes_id');
            $attributes_lists = array_unique($attributes_lists);
            if ($attributes_lists != NULL) {
                foreach ($attributes_lists as $attributes_list) {
                    $product_attr_items = [];
                    foreach ($get_attributes as $get_attribute) {
                        if ($attributes_list == $get_attribute['attributes_id']) {
                            array_push($product_attr_items, $get_attribute);
                            $name = $get_attribute['name'];
                        }
                    }
                    array_push($attributes, ['attribute_id' => $attributes_list, 'attribute_name' => $name, 'attr_items' => $product_attr_items]);
                }
            }
        }
        return $attributes;
    }

}
