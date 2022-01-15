<?php

namespace backend\modules\products\controllers;

use Yii;
use common\models\ImportItems;
use common\models\ImportItemsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * ImportItemsController implements the CRUD actions for ImportItems model.
 */
class ImportItemsController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'ImportItems';
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
     * Lists all ImportItems models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ImportItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ImportItems model.
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
     * Creates a new ImportItems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionImport() {
        $model = new ImportItems();
        $model->scenario = 'import';
//        if (Yii::$app->request->post()) {
        if ($model->load(Yii::$app->request->post())) {
            $profile = UploadedFile::getInstance($model, 'file');
            if (isset($profile)) {
                $inputFileName = $profile->tempName;
                /** Load $inputFileName to a Spreadsheet object * */
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                if (!empty($sheetData)) {
                    $i = 0;
                    $k = 1;
                    if ($profile) {
                        $file_name = md5(microtime());
                        if ($model->uploadFile($profile, $model->id, $file_name)) {
                            $model->file = $file_name . '.' . $profile->extension;
                        }
                    }
//                    echo $model->merchant_id;
                    echo '<pre/>';
//                    print_r($_POST['ImportItems']['']);
////                print_r($sheetData);
//                    exit;
//                $result = [];
                    foreach ($sheetData as $value) {
                        if ($i != 0) {
                            if ($value != NULL) {

                                $pmodel = new \common\models\ProductsServices();
                                foreach ($value as $key => $val) {

                                    if ($key != "A") {
                                        if ($sheetData[$k][$key] == "Item Code") {
                                            $pmodel->sku = $val;
                                        }
                                        $pmodel->merchant_id = $model->merchant_id;
                                        if ($sheetData[$k][$key] == "Name English") {
                                            $pmodel->product_name_en = $val;
                                        }
                                        if ($sheetData[$k][$key] == "Name Arabic") {
                                            $pmodel->product_name_ar = $val;
                                        }
                                        if ($sheetData[$k][$key] == "Description English") {
                                            $pmodel->long_description_en = $val;
                                        }
                                        if ($sheetData[$k][$key] == "Description Arabic") {
                                            $pmodel->long_description_ar = $val;
                                        }
                                        if ($sheetData[$k][$key] == "Sort Order") {
                                            $pmodel->sort_order = $val;
                                        }
                                        if ($sheetData[$k][$key] == "Price") {
                                            $pmodel->price = $val;
                                        }
                                        if ($sheetData[$k][$key] == "Search Tags") {
                                            $pmodel->search_tag = $val;
                                        }
                                        if ($sheetData[$k][$key] == "Related Items") {
                                            $pmodel->related_products = $val;
                                        }
                                        if ($sheetData[$k][$key] == "Stock Availability") {
                                            $pmodel->stock_availability = $val;
                                        }
                                        if ($sheetData[$k][$key] == "Type") {
                                            $pmodel->type = $val;
                                        }
                                        $pmodel->created_by = yii::$app->user->identity->id;
                                        $pmodel->updated_by = yii::$app->user->identity->id;
                                        if (\Yii::$app->user->identity->interface == 'merchant') {
                                            $pmodel->created_by_type = 3;
                                            $pmodel->updated_by_type = 3;
                                        } else if (\Yii::$app->user->identity->interface == 'franchise') {
                                            $pmodel->created_by_type = 4;
                                            $pmodel->updated_by_type = 4;
                                        } else if (\Yii::$app->user->identity->interface == 'admin') {
                                            $pmodel->created_by_type = 2;
                                            $pmodel->updated_by_type = 2;
                                        }
                                    }
                                }
                                $pmodel->save(FALSE);
                                $pmodel->sku = Yii::$app->params['sku_prefix'] . 'M' . $pmodel->merchant_id . 'PS' . $pmodel->id;
                                $pmodel->save(FALSE);
                            }
                        }


//                    if ($i != 0) {
//                        $data = \common\models\ProductsServices::find()->where(['error_code' => $value['B']])->one();
//                        if ($value['A'] == 'CODE') {
//                            if (!empty($data)) {
//                                $data->error_code = $value['B'];
//                                $data->error_title = $value['C'];
//                                $data->error_en = $value['D'];
//                                if ($value['E'] == NULL) {
//
//                                } else {
//                                    $data->error_ar = $value['E'];
//                                }
//
//                                $data->status = 1;
//                                $data->save(FALSE);
//                            } else {
//                                $data = new \common\models\ErrorCode();
//                                $data->error_code = $value['B'];
//                                $data->error_title = $value['C'];
//                                $data->error_en = $value['D'];
//                                if ($value['E'] == NULL) {
//                                    $data->error_ar = "";
//                                } else {
//                                    $data->error_ar = $value['E'];
//                                }
//
//                                $data->status = 1;
//                                $data->save(FALSE);
//                            }
//                        } else if ($value['A'] == 'LANGUAGE') {
//                            $data = \common\models\MobileStrings::find()->where(['string_key' => $value['C']])->one();
//
//                            if (!empty($data)) {
//                                $get_api_settings = \common\models\ApiSettings::find()->where(['status' => 1, 'mobile_string' => 'LOCALIZED_STRING'])->one();
//                                $api_value = $get_api_settings->version;
//                                $get_api_settings->version = $api_value + 1;
//                                $get_api_settings->save(false);
//
//                                $data->module = 'HOME';
//                                $data->string_key = $value['C'];
//                                $data->string_en = $value['D'];
//                                if ($value['E'] == NULL) {
//                                    $data->string_ar = "";
//                                } else {
//                                    $data->string_ar = $value['E'];
//                                }
//                                $data->status = 1;
//                                $data->version = $get_api_settings->version;
//                                $data->save(FALSE);
//                            } else {
//
//
//                                $get_api_settings = \common\models\ApiSettings::find()->where(['status' => 1, 'mobile_string' => 'LOCALIZED_STRING'])->one();
//                                $api_value = $get_api_settings->version;
//                                $get_api_settings->version = $api_value + 1;
//                                $get_api_settings->save(false);
//                                $data = new \common\models\MobileStrings();
//                                $data->module = 'HOME';
//                                $data->string_key = $value['C'];
//                                $data->string_en = $value['D'];
//                                if ($value['E'] == NULL) {
//                                    $data->string_ar = "";
//                                } else {
//                                    $data->string_ar = $value['E'];
//                                }
//                                $data->status = 1;
//                                $data->version = $get_api_settings->version;
//                                $data->save(FALSE);
//                            }
//                        }
//                    }
                        $i++;
                    }
//                    exit;

                    Yii::$app->session->setFlash('success', "Data Imported successfully.");
                    return $this->redirect(['import']);
                } else {
                    Yii::$app->session->setFlash('error', "Data not imported .Please check the excel file that you uploaded.");
                    return $this->redirect(['import']);
                }
            } else {
                Yii::$app->session->setFlash('error', "Data not imported .Please check the excel file that you uploaded.");
                return $this->redirect(['import']);
            }
            exit;
        }
        return $this->render('import', [
                    'model' => $model,
        ]);
    }

    public function actionCreate() {
        $model = new ImportItems();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing ImportItems model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ImportItems model.
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
     * Finds the ImportItems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ImportItems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ImportItems::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
