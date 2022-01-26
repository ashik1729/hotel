<?php

namespace backend\controllers;

use common\models\CarDocuments;
use common\models\CarExtras;
use common\models\CarGeneralInformation;
use common\models\CarOptions;
use Yii;
use common\models\Cars;
use common\models\CarsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * CarsController implements the CRUD actions for Cars model.
 */
class CarsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {

        $tbl_name = 'Cars';
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


    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) {
            return $this->redirect(yii::$app->request->baseUrl . '/site/login');
        }
    }
    /**
     * Lists all Cars models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CarsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cars model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cars model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cars();
        $car_general = new CarGeneralInformation();
        $car_option = new CarOptions();
        $car_extra = new CarExtras();
        $car_docs = new CarDocuments();

        if ($model->load(Yii::$app->request->post())) {
            $canon_name = strtolower($model->title);
            $canonical_name = str_replace(' ', '-', $canon_name); // Replaces all spaces with hyphens.
            $canonical_name = preg_replace('/[^A-Za-z0-9\-]/', '', $canonical_name); // Removes special chars.
            $model->can_name = preg_replace('/-+/', '-', $canonical_name);
            $file = UploadedFile::getInstance($model, 'image');
            $gallery = UploadedFile::getInstances($model, 'gallery');
            $name = md5(microtime());
            $profile_name = 'image' . $name;
            if ($file) {
                $model->image = $profile_name . '.' . $file->extension;
            }

            $model->gallery = "";
            if ($model->save()) {
                $this->saveGeneralData($_POST['CarGeneralInformation'], $model->id);
                $this->saveCarOptions($_POST['CarOptions'], $model->id);
                $this->saveCarExtra($_POST['CarOptions'], $model->id);
                $this->saveCarDocument($_POST['CarOptions'], $model->id);

                if ($file) {
                    $model->uploadFile($file, $profile_name, 'cars/' . $model->id . '/image');
                }

                if ($gallery != NULL) {
                    $model->uploadMultipleImage($gallery, $model->id, $name, 'cars/' . $model->id . '/gallery');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                print_r($model->errors);
                exit;
            }
        }
        return $this->render('create', [
            'model' => $model,
            'car_general' => $car_general,
            'car_option' => $car_option,
            'car_extra' => $car_extra,
            'car_docs' => $car_docs

        ]);
    }
    /**
     * Updates an existing Cars model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $car_general = new CarGeneralInformation();
        $car_option = new CarOptions();
        $car_extra = new CarExtras();
        $car_docs = new CarDocuments();
        $images = $model->image;
        $gallery_data = $model->gallery;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->can_name == "") {
                $canon_name = strtolower($model->title);
                $canonical_name = str_replace(' ', '-', $canon_name); // Replaces all spaces with hyphens.
                $canonical_name = preg_replace('/[^A-Za-z0-9\-]/', '', $canonical_name); // Removes special chars.
                $model->can_name = preg_replace('/-+/', '-', $canonical_name);
            }

            $file = UploadedFile::getInstance($model, 'image');
            $gallery = UploadedFile::getInstances($model, 'gallery');
            $name = md5(microtime());
            $profile_name = 'image' . $name;

            if ($file) {
                $model->image = $profile_name . '.' . $file->extension;
            } else {
                $model->image = $images;
            }

            if ($gallery_data == '') {
                $model->gallery = "";
            } else {
                $model->gallery = $gallery_data;
            }

            if ($model->save()) {
                $this->saveGeneralData($_POST['CarGeneralInformation'], $model->id);
                $this->saveCarOptions($_POST['CarOptions'], $model->id);
                $this->saveCarExtra($_POST['CarOptions'], $model->id);
                $this->saveCarDocument($_POST['CarOptions'], $model->id);
                if ($file) {
                    $model->uploadFile($file, $profile_name, 'cars/' . $model->id . '/image');
                }

                if ($gallery != NULL) {
                    $model->uploadMultipleImage($gallery, $model->id, $name, 'cars/' . $model->id . '/gallery');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                print_r($model->errors);
                exit;
            }
        }

        return $this->render('update', [
            'model' => $model,
            'car_general' => $car_general,
            'car_option' => $car_option,
            'car_extra' => $car_extra,
            'car_docs' => $car_docs

        ]);
    }
    function saveGeneralData($post, $id)
    {
        // echo '<pre/>';
        // print_r($post);
        // exit;
        if ($post != NULL && count($post['ref_id']) > 0) {
            $count =    count($post['ref_id']);
            for ($i = 0; $i < $count; $i++) {
                if (isset($post['ref_id'][$i]) && $post['ref_id'][$i] != "" && isset($post['value'][$i]) && $post['value'][$i] != "") {

                    $carGeneral = new CarGeneralInformation();
                    $checkExist = CarGeneralInformation::find()->where(['car_id' => $id, 'ref_id' => $post['ref_id'][$i]])->one();
                    if ($checkExist != NULL) {

                        $carGeneral = $checkExist;
                    } else {
                        $carGeneral->car_id = $id;
                        $carGeneral->ref_id = $post['ref_id'][$i];
                    }
                    $carGeneral->value = $post['value'][$i];
                    $carGeneral->status = 1;
                    if ($carGeneral->save()) {
                    }
                }
            }
        }
        return true;
    }
    function saveCarDocument($post, $id)
    {
        if ($post != NULL && count($post['ref_id']) > 0) {
            $count =    count($post['ref_id']);
            for ($i = 0; $i < $count; $i++) {
                if (isset($post['ref_id'][$i]) && $post['ref_id'][$i] != "" && isset($post['value'][$i]) && $post['value'][$i] != "") {
                    $carGeneral = new CarDocuments();
                    $checkExist = CarDocuments::find()->where(['car_id' => $id, 'ref_id' => $post['ref_id'][$i]])->one();
                    if ($checkExist != NULL) {
                        $carGeneral = $checkExist;
                    } else {
                        $carGeneral->car_id = $id;
                        $carGeneral->ref_id = $post['ref_id'][$i];
                    }
                    $carGeneral->status = 1;
                    if ($carGeneral->save()) {
                    }
                }
            }
        }
        return true;
    }
    function saveCarOptions($post, $id)
    {
        if ($post != NULL && count($post['ref_id']) > 0) {
            $count =    count($post['ref_id']);
            for ($i = 0; $i < $count; $i++) {
                if (isset($post['ref_id'][$i]) && $post['ref_id'][$i] != "" && isset($post['value'][$i]) && $post['value'][$i] != "") {

                    $carGeneral = new CarOptions();
                    $checkExist = CarOptions::find()->where(['car_id' => $id, 'ref_id' => $post['ref_id'][$i]])->one();
                    if ($checkExist != NULL) {

                        $carGeneral = $checkExist;
                    } else {
                        $carGeneral->car_id = $id;
                        $carGeneral->ref_id = $post['ref_id'][$i];
                    }
                    $carGeneral->value = $post['value'][$i];
                    $carGeneral->status = 1;
                    if ($carGeneral->save()) {
                    }
                }
            }
        }
        return true;
    }
    function saveCarExtra($post, $id)
    {
        if ($post != NULL && count($post['ref_id']) > 0) {
            $count =    count($post['ref_id']);
            for ($i = 0; $i < $count; $i++) {
                if (isset($post['ref_id'][$i]) && $post['ref_id'][$i] != "" && isset($post['value'][$i]) && $post['value'][$i] != "") {

                    $carGeneral = new CarExtras();
                    $checkExist = CarExtras::find()->where(['car_id' => $id, 'ref_id' => $post['ref_id'][$i]])->one();
                    if ($checkExist != NULL) {

                        $carGeneral = $checkExist;
                    } else {
                        $carGeneral->car_id = $id;
                        $carGeneral->ref_id = $post['ref_id'][$i];
                    }
                    $carGeneral->value = $post['value'][$i];
                    $carGeneral->status = 1;
                    if ($carGeneral->save()) {
                    }
                }
            }
        }
        return true;
    }
    /**
     * Deletes an existing Cars model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteInfo()
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            $model = \common\models\CarGeneralInformation::find()->where(['id' => $_POST['item_id']])->one();
            if ($model != NULL) {
                if ($model->delete()) {
                    $array['status'] = 200;
                    $array['error'] = '';
                    $array['message'] = 'Delete Successfully.';
                } else {
                    $array['status'] = 201;
                    $array['error'] = $model->errors;
                    $array['message'] = 'Error.';
                }
            } else {
                $array['status'] = 201;
                $array['error'] = '';
                $array['message'] = 'No such information found.';
            }

            echo json_encode($array);
            exit;
        }
    }
    public function actionDeleteCarOption()
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            $model = \common\models\CarOptions::find()->where(['id' => $_POST['item_id']])->one();
            if ($model != NULL) {
                if ($model->delete()) {
                    $array['status'] = 200;
                    $array['error'] = '';
                    $array['message'] = 'Delete Successfully.';
                } else {
                    $array['status'] = 201;
                    $array['error'] = $model->errors;
                    $array['message'] = 'Error.';
                }
            } else {
                $array['status'] = 201;
                $array['error'] = '';
                $array['message'] = 'No such information found.';
            }

            echo json_encode($array);
            exit;
        }
    }
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    public function actionGalleryDelete()
    {
        $image = $_GET['item'];
        $id = $_GET['id'];
        $model = $this->findModel($id);

        if (is_dir(Yii::$app->basePath . '/../uploads/cars/' . $model->id . '/gallery')) {
            chmod(Yii::$app->basePath . '/../uploads/cars/' . $model->id . '/gallery', 0777);

            $data = Yii::$app->basePath . '/../uploads/cars/' . $model->id . '/gallery/' . $image;
            if (file_exists($data)) {
                chmod($data, 0777);
                unlink($data);
            }

            $gallery = explode(',', $model->gallery);
            $array1 = array($image);
            $array3 = array_diff($gallery, $array1);
            $model->gallery = implode(',', $array3);
            $model->save(FALSE);


            Yii::$app->session->setFlash('success', "Business Gallery image deleted successfully.");
            return $this->redirect(['update', 'id' => $id]);
        }
    }
    /**
     * Finds the Cars model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cars the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cars::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
