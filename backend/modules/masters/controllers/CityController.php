<?php

namespace backend\modules\masters\controllers;

use Yii;
use common\models\City;
use common\models\CitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CityController implements the CRUD actions for City model.
 */
class CityController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'City';
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
     * Lists all City models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new CitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single City model.
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
     * Creates a new City model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new City();
        $areamodel = new \common\models\Area();
        $error = [];
        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->created_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $model->updated_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->save()) {
                if ($_POST['Area'] && count($_POST['Area']['name_en']) > 0) {
                    for ($i = 0; $i < count($_POST['Area']['name_en']); $i++) {
                        if ($_POST['Area']['name_en'][$i]) {
                            $areamodel = new \common\models\Area();
                            $areamodel->name_en = $_POST['Area']['name_en'][$i];
                            $areamodel->name_ar = $_POST['Area']['name_ar'][$i];
                            $areamodel->city = $model->id;
                            $areamodel->sort_order = $_POST['Area']['sort_order'][$i];
                            $areamodel->status = $_POST['Area']['status'][$i];
                            $areamodel->created_by = yii::$app->user->identity->id;
                            $areamodel->updated_by = yii::$app->user->identity->id;
                            $areamodel->created_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                            $areamodel->updated_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                            if (!$areamodel->save()) {
                                $error[] = $areamodel->errors;
                            }
                        }
                    }
                }
                if ($error != NULL) {

                    $transaction->rollBack();
                    print_r($error);
                    exit;
                } else {
                    $transaction->commit();
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
                    'model' => $model,
                    'areamodel' => $areamodel,
        ]);
    }

    /**
     * Updates an existing City model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $areamodel = new \common\models\Area();

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_by = yii::$app->user->identity->id;
            $model->updated_by_type = 1;
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->save()) {
                if ($_POST['Area'] && count($_POST['Area']['name_en']) > 0) {
                    for ($i = 0; $i < count($_POST['Area']['name_en']); $i++) {
                        if ($_POST['Area']['name_en'][$i]) {
                            $check_area_exist = \common\models\Area::find()->where(['id' => $_POST['Area']['id'][$i]])->one();
                            if ($check_area_exist == NULL) {
                                $areamodel = new \common\models\Area();
                            } else {
                                $areamodel = $check_area_exist;
                            }
                            $areamodel->name_en = $_POST['Area']['name_en'][$i];
                            $areamodel->name_ar = $_POST['Area']['name_ar'][$i];
                            $areamodel->city = $model->id;
                            $areamodel->sort_order = $_POST['Area']['sort_order'][$i];
                            $areamodel->status = $_POST['Area']['status'][$i];
                            $areamodel->updated_by = yii::$app->user->identity->id;
                            $areamodel->updated_by_type = 1;
                            if (!$areamodel->save()) {
                                $error[] = $areamodel->errors;
                            }
                        }
                    }
                }
                if ($error != NULL) {

                    $transaction->rollBack();
                    print_r($error);
                    exit;
                } else {
                    $transaction->commit();
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $transaction->rollBack();
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'areamodel' => $areamodel,
        ]);
    }

    /**
     * Deletes an existing City model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteArea() {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $data = "";
            $id = $_POST['id'];
            $get_data = \common\models\Area::find()->where(['id' => $id])->one();
            if ($get_data != NULL) {
                if ($get_data->delete()) {
                    $data = "Success";
                } else {
                    $data = "Error";
                }
            }
            echo $data;
        }
    }

    /**
     * Finds the City model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return City the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = City::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
