<?php

namespace backend\modules\products\controllers;

use Yii;
use common\models\Category;
use common\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'Category';
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Category();
        if ($model->load(Yii::$app->request->post())) {
            $canon_name = strtolower($model->category_name);
            $canonical_name = str_replace(' ', '-', $canon_name); // Replaces all spaces with hyphens.
            $canonical_name = preg_replace('/[^A-Za-z0-9\-]/', '', $canonical_name); // Removes special chars.
            $model->canonical_name = preg_replace('/-+/', '-', $canonical_name);

            if (isset($_POST['search_tag']) && $_POST['search_tag'] != NULL) {
                $implode_search_tag = implode(',', $_POST['search_tag']);
                $model->search_tag = $implode_search_tag;
            }
            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->status = 1;

            $file = UploadedFile::getInstance($model, 'image');
            $gallery = UploadedFile::getInstances($model, 'gallery');
            $name = md5(microtime());
            $profile_name = 'image' . $name;
            if ($file) {
                $model->image = $profile_name . '.' . $file->extension;
            }

            $model->gallery = "";
            if ($model->save()) {
                if ($model->parent == NULL || $model->parent == 0 || $model->parent == '') {
                    $model->parent = $model->id;
                    $model->save(FALSE);
                }
                if ($file) {
                    $model->uploadFile($file, $profile_name, 'category/' . $model->id . '/image');
                }

                if ($gallery != NULL) {
                    $model->uploadMultipleImage($gallery, $model->id, $name, 'category/' . $model->id . '/gallery');
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
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $images = $model->image;
        $gallery_data = $model->gallery;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->canonical_name == "") {
                $canon_name = strtolower($model->category_name);
                $canonical_name = str_replace(' ', '-', $canon_name); // Replaces all spaces with hyphens.
                $canonical_name = preg_replace('/[^A-Za-z0-9\-]/', '', $canonical_name); // Removes special chars.
                $model->canonical_name = preg_replace('/-+/', '-', $canonical_name);
            }
//            $implode_search_tag = implode(',', $model->search_tag);
            if (isset($_POST['search_tag']) && $_POST['search_tag'] != NULL) {
                $implode_search_tag = implode(',', $_POST['search_tag']);
                $model->search_tag = $implode_search_tag;
            }
            $model->updated_by = yii::$app->user->identity->id;

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
            if ($model->parent == NULL || $model->parent == 0 || $model->parent == '') {
                $model->parent = $model->id;
            }
            if ($model->save()) {

                if ($file) {
                    $model->uploadFile($file, $profile_name, 'category/' . $model->id . '/image');
                }

                if ($gallery != NULL) {
                    $model->uploadMultipleImage($gallery, $model->id, $name, 'category/' . $model->id . '/gallery');
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
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionGalleryDelete() {
        $image = $_GET['item'];
        $id = $_GET['id'];
        $model = $this->findModel($id);

        if (is_dir(Yii::$app->basePath . '/../uploads/category/' . $model->id . '/gallery')) {
            chmod(Yii::$app->basePath . '/../uploads/category/' . $model->id . '/gallery', 0777);

            $data = Yii::$app->basePath . '/../uploads/category/' . $model->id . '/gallery/' . $image;
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

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
