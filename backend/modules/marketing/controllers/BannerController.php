<?php

namespace backend\modules\marketing\controllers;

use Yii;
use common\models\Banner;
use common\models\BannerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * BannerController implements the CRUD actions for Banner model.
 */
class BannerController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'Banner';
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
     * Lists all Banner models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new BannerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Banner model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMappingTo() {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $result_html = "";
            if ($_POST['type'] == 1) {
                $get_merchants = \common\models\Merchant::find()->select("id")->where(['status' => 10, 'franchise_id' => $_POST['store']])->asArray()->all();
                $get_merchant_list = array_column($get_merchants, 'id');

                if ($get_merchants != NULL) {
                    $get_data = \common\models\ProductsServices::find()->where(['in', 'merchant_id', $get_merchant_list])->all();
                    $result_html = $this->renderPartial('_getproducts', [
                        'models' => $get_data,
                    ]);
                }
            }
            if ($_POST['type'] == 2) {
                $get_merchants = \common\models\Merchant::find()->select("id")->where(['status' => 10, 'franchise_id' => $_POST['store']])->asArray()->all();
                if ($get_merchants != NULL) {
                    $datas = \common\models\Category::find()->all();
                    $options = array();
                    if ($datas != NULL) {
                        foreach ($datas as $data) {

                            if (!empty($data)) {
                                $option_items = Yii::$app->SelectCategory->selectCategories($data);
                                $option_data = explode('-', $option_items);
                                $option_data_array = array_reverse($option_data);
                                $latest_option = [];
                                if ($option_data_array != NULL) {
                                    foreach ($option_data_array as $option_data_arr) {
                                        $option_cat = \common\models\Category::find()->where(['id' => $option_data_arr])->one();
                                        $latest_option[] = $option_cat->category_name;
                                    }
                                }

                                $option_text = implode(' -> ', $latest_option);

                                $options[$data->id] = $option_text;
                            }
                        }
                    }
                    $result_html = $this->renderPartial('_getcategory', [
                        'models' => $options,
                    ]);
                }
            }
            if ($_POST['type'] == 3) {
                $get_merchants = \common\models\Merchant::find()->select("id,email,business_name,first_name,last_name")->where(['status' => 10, 'franchise_id' => $_POST['store']])->all();
                if ($get_merchants != NULL) {

                    $result_html = $this->renderPartial('_getmerchants', [
                        'models' => $get_merchants,
                    ]);
                }
            }
            $array['status'] = 200;
            $array['error'] = '';
            $array['message'] = $result_html;
            echo json_encode($array);
            exit;
        }
    }

    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Banner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Banner();

        if ($model->load(Yii::$app->request->post())) {

            $file = \yii\web\UploadedFile::getInstance($model, 'file_and');
            $file_ios = \yii\web\UploadedFile::getInstance($model, 'file_ios');
            $name = md5(microtime());
            $file_name = 'file' . $name;
            if ($file) {
                $model->file_and = $file_name . '.' . $file->extension;
            }
            if ($file_ios) {
                $model->file_ios = $file_name . '.' . $file_ios->extension;
            }
            if ($model->promotion_from != "") {
                $days = $model->promotionalCampaign->no_days;
                if ($days != '' && $days != NULL) {
                    $model->promotion_to = date('Y-m-d', strtotime($model->promotion_from . '+' . $days . ' days'));
                }
            }
            $model->promotion_from = date('Y-m-d', strtotime($model->promotion_from));

            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            if ($model->save()) {

                if ($file) {
                    Yii::$app->FileManagement->uploadFile($file, $file_name, 'marketing_banners/' . $model->id . '/android');
                }
                if ($file_ios) {
                    Yii::$app->FileManagement->uploadFile($file_ios, $file_name, 'marketing_banners/' . $model->id . '/ios');
                }
                Yii::$app->session->setFlash('success', "Promotional Banner Created  successfully.");

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
     * Updates an existing Banner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $current_android_image = $model->file_and;
        $current_ios_image = $model->file_ios;
        if ($model->load(Yii::$app->request->post())) {

            $file = \yii\web\UploadedFile::getInstance($model, 'file_and');
            $file_ios = \yii\web\UploadedFile::getInstance($model, 'file_ios');
            $name = md5(microtime());
            $file_name = 'file' . $name;
            if ($file) {
                $model->file_and = $file_name . '.' . $file->extension;
            } else {
                $model->file_and = $current_android_image;
            }
            if ($file_ios) {
                $model->file_ios = $file_name . '.' . $file_ios->extension;
            } else {
                $model->file_ios = $current_ios_image;
            }
            if ($model->promotion_from != "") {
                $days = $model->promotionalCampaign->no_days;
                if ($days != '' && $days != NULL) {
                    $model->promotion_to = date('Y-m-d', strtotime($model->promotion_from . '+' . $days . ' days'));
                }
            }
            $model->promotion_from = date('Y-m-d', strtotime($model->promotion_from));

            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            if ($model->save()) {

                if ($file) {
                    Yii::$app->FileManagement->uploadFile($file, $file_name, 'marketing_banners/' . $model->id . '/android');
                }
                if ($file_ios) {
                    Yii::$app->FileManagement->uploadFile($file_ios, $file_name, 'marketing_banners/' . $model->id . '/ios');
                }
                Yii::$app->session->setFlash('success', "Promotional Banner Created  successfully.");

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
     * Deletes an existing Banner model.
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
     * Finds the Banner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Banner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Banner::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
