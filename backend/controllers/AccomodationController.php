<?php

namespace backend\controllers;

use Yii;
use common\models\Accomodation;
use common\models\AccomodationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * AccomodationController implements the CRUD actions for Accomodation model.
 */
class AccomodationController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {

        $tbl_name = 'Accomodation';
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
     * Lists all Accomodation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccomodationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Accomodation model.
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
     * Creates a new Accomodation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Accomodation();

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

                if ($file) {
                    $model->uploadFile($file, $profile_name, 'accomodation/' . $model->id . '/image');
                }

                if ($gallery != NULL) {
                    $model->uploadMultipleImage($gallery, $model->id, $name, 'accomodation/' . $model->id . '/gallery');
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
     * Updates an existing Accomodation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

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

                if ($file) {
                    $model->uploadFile($file, $profile_name, 'accomodation/' . $model->id . '/image');
                }

                if ($gallery != NULL) {
                    $model->uploadMultipleImage($gallery, $model->id, $name, 'accomodation/' . $model->id . '/gallery');
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
     * Deletes an existing Accomodation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionGalleryDelete() {
        $image = $_GET['item'];
        $id = $_GET['id'];
        $model = $this->findModel($id);

        if (is_dir(Yii::$app->basePath . '/../uploads/accomodation/' . $model->id . '/gallery')) {
            chmod(Yii::$app->basePath . '/../uploads/accomodation/' . $model->id . '/gallery', 0777);

            $data = Yii::$app->basePath . '/../uploads/accomodation/' . $model->id . '/gallery/' . $image;
            if (file_exists($data)) {
                chmod($data, 0777);
                unlink($data);
            }

            $gallery = explode(',', $model->gallery);
            $array1 = Array($image);
            $array3 = array_diff($gallery, $array1);
            $model->gallery = implode(',', $array3);
            $model->save(FALSE);


            Yii::$app->session->setFlash('success', "Business Gallery image deleted successfully.");
            return $this->redirect(['update', 'id' => $id]);
        }
    }
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Accomodation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Accomodation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Accomodation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}