<?php

namespace frontend\controllers;

use common\models\OrderProducts;
use common\models\Orders;
use common\models\ProductReview;
use common\models\VisaRequests;
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
class MyAccountController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
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

    public function actionDashboard()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        return $this->render('dashboard');
    }

    public function actionVisaEnquiry()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $visaEnquiryQuery = VisaRequests::find()->where(['user_id' => Yii::$app->user->id]);

        $productReview = new   ProductReview();
     
        if ($productReview->load(Yii::$app->request->post())) {
            // $reviewError = 0;
            $productReview->user_id = Yii::$app->user->id;
            $productReview->review_type = 2;
            $productReview->approvel = 0;
            $productReview->created_by = Yii::$app->user->id;
            $productReview->updated_by = Yii::$app->user->id;
            if ($productReview->save()) {
                // $reviewError = 1;
                Yii::$app->session->setFlash('success', "Visa Feedback Sent Successfully");
                return  $this->redirect(['visa-enquiry']);

            }
          
        }
        if (isset($_REQUEST['period']) && $_REQUEST['period'] != "") {
            $sixmonthAgo = date("Y-m-d", strtotime("-6 months"));
            $time = strtotime("-1 year", time());
            $oneYear = date("Y-m-d", $time);
            if ($_REQUEST['period'] == 1) {
                $visaEnquiryQuery->andWhere("DATE(created_at) > '" . $sixmonthAgo . "'");
            }
            if ($_REQUEST['period'] == 2) {
                $visaEnquiryQuery->andWhere("DATE(created_at) > '" . $oneYear . "'");
            }
        }
        $visaEnquiry = $visaEnquiryQuery->all();
        return $this->render('visa-enquiry', ['visaEnquiry' => $visaEnquiry,'productReview'=>$productReview]);
    }
    public function actionCancelBooking()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
      
        if (isset($_POST['package_id']) && $_POST['package_id'] != "") {
            $getPackageRequest = OrderProducts::find()->where(['id' => $_POST['package_id'], 'user_id' => Yii::$app->user->id])->one();
            if ($getPackageRequest != NULL) {
               
                $getPackageRequest->status = 9;
                if($getPackageRequest->save()){
                    $addHistory = $this->addToHistory($getPackageRequest,9);
                    if($addHistory == NULL){
                        Yii::$app->session->setFlash('success', "Booking Cancelled Successfully");
                        return  $this->redirect(['package-history']);
                    }else{
                        print_r($addHistory);
                        exit;
                    }
                }
            }
        }
    }
    public function addToHistory($orderProducts,$status)
    { // Calculate Order Amount
        $order_history_error = [];
        $order_history = new \common\models\OrderHistory();
        $order_history->order_id = $orderProducts->order_id;
        $order_history->order_product_id = $orderProducts->id;
        $order_history->order_status = $status;
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
    public function actionPackageHistory()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $query = Orders::find()->where(['user_id' => Yii::$app->user->id]);
        $productReview = new   ProductReview();
     
        if ($productReview->load(Yii::$app->request->post())) {
            // $reviewError = 0;
            $productReview->user_id = Yii::$app->user->id;
            $productReview->review_type = 1;
            $productReview->approvel = 0;
            $productReview->created_by = Yii::$app->user->id;
            $productReview->updated_by = Yii::$app->user->id;
            if ($productReview->save()) {
                // $reviewError = 1;
                Yii::$app->session->setFlash('success', "Feedback Sent Successfully");
                return  $this->redirect(['package-history']);

            }
          
        }
        $productReview = new   ProductReview(); 
        if (isset($_REQUEST['period']) && $_REQUEST['period'] != "") {
            $sixmonthAgo = date("Y-m-d", strtotime("-6 months"));
            $time = strtotime("-1 year", time());
            $oneYear = date("Y-m-d", $time);
            if ($_REQUEST['period'] == 1) {
                $query->andWhere("DATE(created_at) > '" . $sixmonthAgo . "'");
            }
            if ($_REQUEST['period'] == 2) {
                $query->andWhere("DATE(created_at) > '" . $oneYear . "'");
            }
        }
        $orders = $query->all();

        return $this->render('package-history', ['orders' => $orders,'productReview'=>$productReview]);
    }
}
