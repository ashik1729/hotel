<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
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

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionError() {
        return $this->render('error');
    }

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(yii::$app->request->baseUrl . '/site/dashboard');
        } else {

            return $this->redirect(yii::$app->request->baseUrl . '/site/login');
        }
    }

    public function actionDashboard() {
        $ordersQuery = \common\models\Orders::find()->where("status != 0");
        if (\Yii::$app->user->identity->interface == 'merchant') {
            $ordersQuery->innerJoinWith('order_products', 'orders.id = order_products.order_id');
            $ordersQuery->andWhere(['order_products.merchant_id' => \Yii::$app->user->identity->interface]);
        }
        if (\Yii::$app->user->identity->interface == 'franchise') {
            $get_merchant = Merchant::find()->select('id')->where(['franchise_id' => \Yii::$app->user->identity->id])->asArray()->all();
            $merchant_array = array_column($get_merchant, 'id');
            $ordersQuery->innerJoinWith('order_products', 'orders.id = order_products.order_id');
            $ordersQuery->andWhere(['order_products.merchant_id' => $merchant_array]);
        }
        $orders = $ordersQuery->all();

        return $this->render('index', ['orders' => $orders]);
//        return $this->redirect(['dashboard']);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {

        Yii::$app->user->logout();

        return $this->goHome();
    }

}
