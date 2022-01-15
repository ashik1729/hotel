<?php

namespace backend\controllers;

use Yii;
use common\models\MobileStrings;
use common\models\MobileStringsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * MobileStringsController implements the CRUD actions for MobileStrings model.
 */
class MobileStringsController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'MobileStrings';
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
     * Lists all MobileStrings models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MobileStringsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MobileStrings model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionImport() {
        $model = new MobileStrings();
        $model->scenario = 'import';
        if (Yii::$app->request->post()) {

            $profile = UploadedFile::getInstance($model, 'import');
            $inputFileName = $profile->tempName;
            /** Load $inputFileName to a Spreadsheet object * */
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            if (!empty($sheetData)) {
                $i = 0;


                if ($profile) {
                    $model->uploadFile($profile, $model->id);
                }
//                print_r($spreadsheet);
                foreach ($sheetData as $value) {


                    if ($i != 0) {
                        if ($value['A'] == 'CODE') {
                            $data = \common\models\ErrorCode::find()->where(['error_code' => $value['B']])->one();
                            if (!empty($data)) {
                                $data->error_code = $value['B'];
                                $data->error_title = $value['C'];
                                $data->error_en = $value['D'];
                                if ($value['E'] == NULL) {

                                } else {
                                    $data->error_ar = $value['E'];
                                }

                                $data->status = 1;
                                $data->save(FALSE);
                            } else {
                                $data = new \common\models\ErrorCode();
                                $data->error_code = $value['B'];
                                $data->error_title = $value['C'];
                                $data->error_en = $value['D'];
                                if ($value['E'] == NULL) {
                                    $data->error_ar = "";
                                } else {
                                    $data->error_ar = $value['E'];
                                }

                                $data->status = 1;
                                $data->save(FALSE);
                            }
                        } else if ($value['A'] == 'LANGUAGE') {
                            $data = \common\models\MobileStrings::find()->where(['string_key' => $value['C']])->one();

                            if (!empty($data)) {
                                $get_api_settings = \common\models\ApiSettings::find()->where(['status' => 1, 'mobile_string' => 'LOCALIZED_STRING'])->one();
                                $api_value = $get_api_settings->version;
                                $get_api_settings->version = $api_value + 1;
                                $get_api_settings->save(false);

                                $data->module = 'HOME';
                                $data->string_key = $value['C'];
                                $data->string_en = $value['D'];
                                if ($value['E'] == NULL) {
                                    $data->string_ar = "";
                                } else {
                                    $data->string_ar = $value['E'];
                                }
                                $data->status = 1;
                                $data->version = $get_api_settings->version;
                                $data->save(FALSE);
                            } else {


                                $get_api_settings = \common\models\ApiSettings::find()->where(['status' => 1, 'mobile_string' => 'LOCALIZED_STRING'])->one();
                                $api_value = $get_api_settings->version;
                                $get_api_settings->version = $api_value + 1;
                                $get_api_settings->save(false);
                                $data = new \common\models\MobileStrings();
                                $data->module = 'HOME';
                                $data->string_key = $value['C'];
                                $data->string_en = $value['D'];
                                if ($value['E'] == NULL) {
                                    $data->string_ar = "";
                                } else {
                                    $data->string_ar = $value['E'];
                                }
                                $data->status = 1;
                                $data->version = $get_api_settings->version;
                                $data->save(FALSE);
                            }
                        }
                    }
                    $i++;
                }
                Yii::$app->session->setFlash('success', "Data Imported successfully.");
                return $this->redirect(['import']);
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

//public function actionImport() {
//
//
//        $model = new ErrorCode();
//        $model->scenario = 'import';
//        if (Yii::$app->request->post()) {
//            $profile = UploadedFile::getInstance($model, 'import');
//
//            $pFilename = $profile->tempName;
//            $objPHPExcel = \PHPExcel_IOFactory::load($pFilename);
//            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
//            if (!empty($sheetData)) {
//                $i = 0;
//
//
//                if ($profile) {
//                    $model->uploadFile($profile, $model->id);
//                }
//                foreach ($sheetData as $value) {
//                    if ($i != 0) {
//                        if ($value['A'] == 'CODE') {
//                            $data = ErrorCode::find()->where(['error_code' => $value['B']])->one();
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
//                                $data = new ErrorCode();
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
//                    $i++;
//                }
//
//                Yii::$app->session->setFlash('success', "Data Imported successfully.");
//                return $this->redirect(['import']);
//            } else {
//                Yii::$app->session->setFlash('error', "Data not imported .Please check the excel file that you uploaded.");
//                return $this->redirect(['import']);
//            }
//        }
//        return $this->render('import', [
//                    'model' => $model,
//        ]);
//    }

    /**
     * Creates a new MobileStrings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MobileStrings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing MobileStrings model.
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
     * Deletes an existing MobileStrings model.
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
     * Finds the MobileStrings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MobileStrings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MobileStrings::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
