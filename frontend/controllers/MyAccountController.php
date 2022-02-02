<?php

namespace frontend\controllers;

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
        if (isset($_REQUEST['period']) && $_REQUEST['period'] != "") {
            $sixmonthAgo = date("Y-m-d", strtotime("-6 months"));
            $time = strtotime("-1 year", time());
            $oneYear = date("Y-m-d", $time);
            if ($_REQUEST['period'] == 1) {
                $visaEnquiryQuery->andWhere("DATE(created_at) > '" . $sixmonthAgo."'");
            }
            if ($_REQUEST['period'] == 2) {
                $visaEnquiryQuery->andWhere("DATE(created_at) > '" . $oneYear."'");
            }
        }
        $visaEnquiry = $visaEnquiryQuery->all();
        return $this->render('visa-enquiry', ['visaEnquiry' => $visaEnquiry]);
    }
    public function actionPackageHistory()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $visaEnquiryQuery = VisaRequests::find()->where(['user_id' => Yii::$app->user->id]);
        if (isset($_REQUEST['period']) && $_REQUEST['period'] != "") {
            $sixmonthAgo = date("Y-m-d", strtotime("-6 months"));
            $time = strtotime("-1 year", time());
            $oneYear = date("Y-m-d", $time);
            if ($_REQUEST['period'] == 1) {
                $visaEnquiryQuery->andWhere("DATE(created_at) > '" . $sixmonthAgo."'");
            }
            if ($_REQUEST['period'] == 2) {
                $visaEnquiryQuery->andWhere("DATE(created_at) > '" . $oneYear."'");
            }
        }
        $visaEnquiry = $visaEnquiryQuery->all();
        return $this->render('package-history', ['visaEnquiry' => $visaEnquiry]);
    }
}
