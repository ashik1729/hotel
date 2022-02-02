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
