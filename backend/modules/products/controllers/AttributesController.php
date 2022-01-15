<?php

namespace backend\modules\products\controllers;

use Yii;
use common\models\Attributes;
use common\models\AttributesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AttributesController implements the CRUD actions for Attributes model.
 */
class AttributesController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'Attributes';
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
     * Lists all Attributes models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new AttributesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Attributes model.
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
     * Creates a new Attributes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Attributes();

        if ($model->load(Yii::$app->request->post())) {

            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->created_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $model->updated_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $model->sort_order = 0;

            if ($model->save()) {
                if (isset($_POST['attr_value']) && $_POST['attr_value'] != NULL) {


                    $attr_vals = $_POST['attr_value'];
                    foreach ($attr_vals as $attr_val) {
                        $newmodel = new \common\models\AttributesValue();
                        $newmodel->attributes_id = $model->id;
                        $newmodel->value = $attr_val;
                        $newmodel->status = $model->id;
                        $newmodel->created_by = yii::$app->user->identity->id;
                        $newmodel->updated_by = yii::$app->user->identity->id;
                        $newmodel->created_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                        $newmodel->updated_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                        $newmodel->sort_order = 0;
                        if ($newmodel->save()) {

                        } else {
                            print_r($newmodel->errors);
                        }
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                //  print_r($model->errors);
                //exit;
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Attributes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->updated_by = yii::$app->user->identity->id;
            $model->updated_by_type = 1;
            $model->sort_order = 0;

            if ($model->save()) {
                if (isset($_POST['attr_value']) && $_POST['attr_value'] != NULL) {


                    $attr_vals = $_POST['attr_value'];
                    foreach ($attr_vals as $attr_val) {
                        $check_model = \common\models\AttributesValue::find()->where(['value' => $attr_val, 'attributes_id' => $model->id])->one();
                        if ($check_model == NULL) {
                            $newmodel = new \common\models\AttributesValue();
                            $newmodel->attributes_id = $model->id;
                            $newmodel->value = $attr_val;
                            $newmodel->status = $model->id;
                            $newmodel->created_by = yii::$app->user->identity->id;
                            $newmodel->updated_by = yii::$app->user->identity->id;
                            $newmodel->created_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                            $newmodel->updated_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                            $newmodel->sort_order = 0;
                            if ($newmodel->save()) {

                            } else {
                                print_r($newmodel->errors);
                            }
                        }
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                //  print_r($model->errors);
                //exit;
            }
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Attributes model.
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
     * Finds the Attributes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Attributes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Attributes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
