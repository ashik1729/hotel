<?php

namespace backend\modules\users\controllers;

use Yii;
use common\models\MerchantFeatureList;
use common\models\MerchantFeatureListSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * MerchantFeatureListController implements the CRUD actions for MerchantFeatureList model.
 */
class MerchantFeatureListController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'MerchantFeatureList';
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
     * Lists all MerchantFeatureList models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MerchantFeatureListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MerchantFeatureList model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        if (Yii::$app->user->identity->interface == 'merchant') {
            if ($model->merchant_id != Yii::$app->user->identity->id) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MerchantFeatureList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MerchantFeatureList();
        $modelfet = new \common\models\FeaturesList();
        if ($model->load(Yii::$app->request->post())) {

            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $model->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $transaction = Yii::$app->db->beginTransaction();
            if (\Yii::$app->user->identity->interface == 'merchant' || \Yii::$app->user->identity->interface == 'franchise') {
                $model->status = 0;
            }
            try {
                $check_exist = \common\models\FeaturesList::find()->where(['id' => $model->feature_id])->one();
                if ($check_exist != NULL) {
                    if ($model->save()) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {

//                        print_r($model->errors);
//                        exit;
                        $transaction->rollback(/* your params */);
                    }
                } else {
                    $modelfet->title = $model->feature_id;
                    $modelfet->name_en = $model->feature_id;
                    $modelfet->name_ar = $model->feature_id;
                    $modelfet->created_by = yii::$app->user->identity->id;
                    $modelfet->updated_by = yii::$app->user->identity->id;
                    $modelfet->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    $modelfet->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    $modelfet->sort_order = 0;
                    if (\Yii::$app->user->identity->interface == 'merchant' || \Yii::$app->user->identity->interface == 'franchise') {
                        $modelfet->status = 0;
                    } else {
                        $modelfet->status = 1;
                    }
                    if ($modelfet->save()) {
                        $model->feature_id = $modelfet->id;
                        if ($model->save()) {
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        } else {

//                            print_r($model->errors);
//                            exit;
                            $transaction->rollback(/* your params */);
                        }
                    } else {
//                        print_r($modelfet->errors);
//                        exit;
                        $transaction->rollback(/* your params */);
                    }
                }
            } catch (Exception $e) {
                $transaction->rollback(/* your params */);
                throw $e;
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'modelfet' => $modelfet,
        ]);
    }

    /**
     * Updates an existing MerchantFeatureList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $modelfet = new \common\models\FeaturesList();
        if (Yii::$app->user->identity->interface == 'merchant') {
            if ($model->merchant_id != Yii::$app->user->identity->id) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $model->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $transaction = Yii::$app->db->beginTransaction();
            if (\Yii::$app->user->identity->interface == 'merchant' || \Yii::$app->user->identity->interface == 'franchise') {
                $model->status = 0;
            }
            try {
                $check_exist = \common\models\FeaturesList::find()->where(['id' => $model->feature_id])->one();
                if ($check_exist != NULL) {
                    if (\Yii::$app->user->identity->interface == 'admin') {
                        $check_exist->status = 1;
                        $check_exist->save(FALSE);
                    }
                    if ($model->save()) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        print_r($model->errors);
                        exit;
                        $transaction->rollback(/* your params */);
                    }
                } else {
                    $modelfet->title = $model->feature_id;
                    $modelfet->name_en = $model->feature_id;
                    $modelfet->name_ar = $model->feature_id;
                    $modelfet->created_by = yii::$app->user->identity->id;
                    $modelfet->updated_by = yii::$app->user->identity->id;
                    $modelfet->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    $modelfet->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                    $modelfet->sort_order = 0;
                    if (\Yii::$app->user->identity->interface == 'merchant' || \Yii::$app->user->identity->interface == 'franchise') {
                        $modelfet->status = 0;
                    } else {
                        $modelfet->status = 1;
                    }
                    if ($modelfet->save()) {
                        $model->feature_id = $modelfet->id;
                        if ($model->save()) {
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        } else {
                            print_r($model->errors);
                            exit;
                            $transaction->rollback(/* your params */);
                        }
                    } else {
                        print_r($modelfet->errors);
                        exit;
                        $transaction->rollback(/* your params */);
                    }
                }
            } catch (Exception $e) {
                $transaction->rollback(/* your params */);
                throw $e;
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'modelfet' => $modelfet,
        ]);
    }

    /**
     * Deletes an existing MerchantFeatureList model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);

        if (Yii::$app->user->identity->interface == 'merchant') {
            if ($model->merchant_id != Yii::$app->user->identity->id) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MerchantFeatureList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MerchantFeatureList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MerchantFeatureList::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
