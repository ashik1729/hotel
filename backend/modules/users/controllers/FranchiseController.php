<?php

namespace backend\modules\users\controllers;

use Yii;
use common\models\Franchise;
use common\models\FranchiseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * FranchiseController implements the CRUD actions for Franchise model.
 */
class FranchiseController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'Franchise';
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
     * Lists all Franchise models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new FranchiseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Franchise model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Franchise model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Franchise();

        if ($model->load(Yii::$app->request->post())) {

            $password = $model->password;
            $model->password = Yii::$app->security->generatePasswordHash($password);
            $model->generateAuthKey();
            $file = UploadedFile::getInstance($model, 'profile_image');
            $name = md5(microtime());
            if ($file) {
                $model->profile_image = $name . '.' . $file->extension;
            }
            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->access_token = bin2hex(random_bytes(96));
            $model->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            if ($model->save()) {
                if ($file) {
                    $model->uploadFile($file, $name, 'franchise');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                print_r($model->errors);
                exit;
            }
        }


        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Franchise model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $images = $model->profile_image;
        $old_password = $model->password;

        if ($model->load(Yii::$app->request->post())) {

            if ($old_password != $model->password) {
                $model->setPassword($model->password);
                $model->generateAuthKey();
            }
            $file = UploadedFile::getInstance($model, 'profile_image');
            $name = md5(microtime());
            if ($file) {
                $model->profile_image = $name . '.' . $file->extension;
            } else {
                $model->profile_image = $images;
            }
//            $model->access_token = bin2hex(random_bytes(96));
            $model->updated_by = yii::$app->user->identity->id;
            $model->updated_by_type = 1;
            if ($model->save()) {
                if ($file) {
                    $model->uploadFile($file, $name, 'franchise');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                print_r($model->errors);
                exit;
            }
        }


        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Franchise model.
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
     * Finds the Franchise model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Franchise the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Franchise::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
