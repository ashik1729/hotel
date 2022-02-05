<?php

namespace frontend\controllers;

use common\components\Order;
use common\models\Cart;
use common\models\Orders;
use common\models\UserAddress;
use Yii;
use yii\web\Controller;
use frontend\models\LoginForm;
use yii\web\Response;
use yii\helpers\Json;
use yii\filters\Cors;
use yii\web\UploadedFile;
use frontend\controllers\CrmController;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\VerifyEmailForm;
use InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class CheckoutController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'index', 'error', 'register', 'verify-email', 'log-out', 'forgot-password', 'reset-password', 'reset-password-success'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'dashboard'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }


    public function actionError()
    {

        $this->layout = 'defaultLayoutName';
        //rest of the code goes here
    }
    public function orderProducts($order, $cart_lists)
    { // Calculate Order Amount
        $errors = [];
        if ($cart_lists != NULL) {
            foreach ($cart_lists as $cart) {
                $cart = \common\models\Cart::findOne(['id' => $cart->id, 'status' => 1]);
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
    public function actionIndex()
    {
        $models = Cart::find()->where(['user_id' => Yii::$app->user->id, 'status' => 1])->all();
        $order = new Orders();
        $userAddress = new UserAddress();
        if ($order->load(Yii::$app->request->post())) {
            $getCartslist = \common\models\Cart::find()->where(['user_id' => Yii::$app->user->id, 'status' => 1])->all();
            if (isset($_POST['coupon_code']) && $_POST['coupon_code'] != "") {
                $coupondata = $this->getCoupon($_POST);
                if ($coupondata['status'] && $coupondata['status'] == TRUE) {
                    $coupon = $coupondata['amount'];
                } else {

                    $coupon = 0;
                }
            } else {
                $coupon = 0;
            }
            $tax = 0;
            $totalShippingCharge = 0;
            $total_amount = $this->grandTotal($getCartslist, $totalShippingCharge, $coupon, $tax);

            $transaction = Yii::$app->db->beginTransaction();
            $address  =  $this->getAddress($_POST['UserAddress']);
            $order->user_id = Yii::$app->user->id;
            $order->store = 3;
            $order->ship_address = $address;
            $order->bill_address = $address;
            $order->payment_method = $_POST['payment_method'];  //1-Card/Online,2-cash
            $order->payment_status = 0; // 0-Pending, 1-Success,2-Failed
            $order->status = 1;   // 0- Abonded/Deleted, 1- Pending, 2-ORder Placed, 3- Shipped, 4-Delivered, 5-Completed, 6- Returned , 7-Cancelled
            $order->created_by = Yii::$app->user->id;;
            $order->updated_by = Yii::$app->user->id;;
            $order->updated_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $order->created_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $order->total_amount = $total_amount;
            if ($order->save()) { // Creating Order is success
                $order_product = $this->orderProducts($order, $getCartslist);
                if ($order_product != NULL) {
                    $transaction->commit();
                } else {
                    $transaction->rollBack();
                }
            } else {
                $transaction->rollBack();
            }
            // if (!Yii::$app->user->isGuest) {
            //     $checkCart = Cart::find()->where(['user_id'=>Yii::$app->user->id,'date'=>$_POST['Cart']['date'],'product_id'=>$model->id])->one();
            //     if($checkCart != NULL){
            //         $cart = $checkCart;
            //     }
            //     $cart->id = strtoupper(uniqid('HCCA'));
            //     $cart->user_id = Yii::$app->user->id;
            //     $cart->product_id = $model->id;
            //     $cart->quantity = 1;
            //     $cart->status = 1;
            //     if ($cart->save()) {
            //         Yii::$app->session->setFlash('success', "Package Added  Successfully.");
            //         return  $this->redirect(['book-package-details/' . $cart->id]);
            //     } else {
            //         Yii::$app->session->setFlash('error', "Following Error While Adding to your package." . json_encode($cart->errors));
            //         return  $this->redirect(['book-package/' . $_GET['can']]);
            //     }
            // } else {
            //     Yii::$app->session->setFlash('error', "Please Login before making Booking request.");
            //     return  $this->redirect(['book-package/' . $_GET['can']]);
            // }
        }
        return $this->render('index', ['models' => $models, 'order' => $order, 'userAddress' => $userAddress]);
    }
    //Register as User/Merchant based on email, facebook,gmail

}
