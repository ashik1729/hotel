<?php

namespace backend\modules\users\controllers;

use Yii;
use common\models\FeaturesList;
use common\models\FeaturesListSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * FeaturesListController implements the CRUD actions for FeaturesList model.
 */
class FeaturesListController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'FeaturesList';
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
     * Lists all FeaturesList models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new FeaturesListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FeaturesList model.
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
     * Creates a new FeaturesList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new FeaturesList();

        if ($model->load(Yii::$app->request->post())) {
            $model->title = $model->name_en;
            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $model->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            if ($model->save()) {

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing FeaturesList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->title = $model->name_en;
            $model->updated_by = yii::$app->user->identity->id;
            $model->updated_by_type = 1;
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing FeaturesList model.
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
     * Finds the FeaturesList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FeaturesList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = FeaturesList::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
