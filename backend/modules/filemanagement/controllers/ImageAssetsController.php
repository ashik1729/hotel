<?php

namespace backend\modules\filemanagement\controllers;

use Yii;
use common\models\ImageAssets;
use common\models\ImageAssetsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * ImageAssetsController implements the CRUD actions for ImageAssets model.
 */
class ImageAssetsController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'ImageAssets';
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
     * Lists all ImageAssets models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ImageAssetsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ImageAssets model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function version() {

        $check_exist = \common\models\ImageAssets::find()->where(['status' => 1])->max('version');
        if ($check_exist != NULL) {
            $get_current_version = $check_exist + 0.01;
        } else {
            $get_current_version = 1;
        }
        return $get_current_version;
    }

    /**
     * Creates a new ImageAssets model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ImageAssets();

        if ($model->load(Yii::$app->request->post())) {

            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $model->version = $this->version();
            $file = UploadedFile::getInstance($model, 'image');
            $name = md5(microtime());
            $img_name = 'img-' . strtotime(date('Y-m-d H:i:s')) . '-' . $name;
            if ($file) {
                $model->image = $img_name . '.' . $file->extension;
            }
            $transaction = Yii::$app->db->beginTransaction();

            if ($model->save()) {

                $update_image_type = \common\models\ImageType::find()->where(['id' => $model->type])->one();
                if ($update_image_type != NULL) {
                    $update_image_type->version = $model->version;
                    if ($update_image_type->save()) {
                        if ($file) {
                            if (Yii::$app->FileManagement->uploadFile($file, $img_name, 'filemanagement/' . base64_encode($model->id))) {
                                $transaction->commit();
                                return $this->redirect(['view', 'id' => $model->id]);
                            } else {
                                $transaction->rollBack();
                            }
                        } else {
                            $transaction->rollBack();
                        }
                    } else {

                        $transaction->rollBack();
                    }
                } else {
                    $transaction->rollBack();
                }
            } else {

                $transaction->rollBack();
            }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing ImageAssets model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $ext_image_name = $model->image;
        if ($model->load(Yii::$app->request->post())) {

            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $model->updated_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $model->version = $this->version();
            $file = UploadedFile::getInstance($model, 'image');
            $name = md5(microtime());
            $img_name = 'img-' . strtotime(date('Y-m-d H:i:s')) . '-' . $name;
            if ($file) {
                $model->image = $img_name . '.' . $file->extension;
            } else {
                $model->image = $ext_image_name;
            }
            $transaction = Yii::$app->db->beginTransaction();

            if ($model->save()) {
                $update_image_type = \common\models\ImageType::find()->where(['id' => $model->type])->one();
                if ($update_image_type != NULL) {
                    $update_image_type->version = $model->version;
                    if ($update_image_type->save()) {
                        if ($file) {
                            if (Yii::$app->FileManagement->uploadFile($file, $img_name, 'filemanagement/' . base64_encode($model->id))) {
                                $transaction->commit();
                                return $this->redirect(['view', 'id' => $model->id]);
                            } else {
                                $transaction->rollBack();
                            }
                        } else {
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } else {
                        print_r($update_image_type->errors);
                        exit;
                        $transaction->rollBack();
                    }
                } else {
                    $transaction->rollBack();
                }
            } else {
                print_r($model->errors);
                exit;
                $transaction->rollBack();
            }
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ImageAssets model.
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
     * Finds the ImageAssets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ImageAssets the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ImageAssets::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
