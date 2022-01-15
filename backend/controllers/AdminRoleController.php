<?php

namespace backend\controllers;

use Yii;
use common\models\AdminRole;
use common\models\AdminRoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminRoleController implements the CRUD actions for AdminRole model.
 */
class AdminRoleController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
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
     * Lists all AdminRole models.
     * @return mixed
     */
    function actionGetmodulecontrollersandactions($modulecontrollerDir) {


        $fulllist = [];


        //  $dirs = array_filter(glob('*'), 'is_dir');
        $directories = glob($modulecontrollerDir . '/*', GLOB_ONLYDIR);
        if ($directories != NULL) {
            foreach ($directories as $directorie) {
                $controllerlist = [];

                $controllerDir = \Yii::getAlias($directorie . '/controllers');

                if ($handle = opendir($controllerDir)) {

                    while (false !== ($file = readdir($handle))) {
                        if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                            $controllerlist[] = $file;
                        }
                    }
                    closedir($handle);
                }

                asort($controllerlist);

                foreach ($controllerlist as $controller):
                    $handle = fopen($controllerDir . '/' . $controller, "r");
                    if ($handle) {
                        while (($line = fgets($handle)) !== false) {
                            if (preg_match('/public function action(.*?)\(/', $line, $display)):
                                if (strlen($display[1]) > 2):
                                    $fulllist[substr($controller, 0, -4)][] = strtolower(preg_replace('~(?=[A-Z])(?!\A)~', '-', $display[1]));
                                endif;
                            endif;
                        }
                    }
                    fclose($handle);
                endforeach;
            }
        }

        return $fulllist;
    }

    function actionGetcontrollersandactions($controllerDir) {


        $controllerlist = [];
        if ($handle = opendir($controllerDir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                    $controllerlist[] = $file;
                }
            }
            closedir($handle);
        }
        asort($controllerlist);
        $fulllist = [];
        foreach ($controllerlist as $controller):
            $handle = fopen($controllerDir . '/' . $controller, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (preg_match('/public function action(.*?)\(/', $line, $display)):
                        if (strlen($display[1]) > 2):
                            $fulllist[substr($controller, 0, -4)][] = strtolower(preg_replace('~(?=[A-Z])(?!\A)~', '-', $display[1]));
                        endif;
                    endif;
                }
            }
            fclose($handle);
        endforeach;
        return $fulllist;
    }

    public function actionIndex() {
        date_default_timezone_set('Asia/Qatar');
        $controllerDirs = [];
        $controllerDirs[] = \Yii::getAlias('@backend/controllers');
        $controllerDirsModbase = \Yii::getAlias('@backend/modules');
        $actionsmodule = [];
        $actions = [];
        $action_lists = [];
        $actionsmodule = $this->actionGetmodulecontrollersandactions($controllerDirsModbase);

        foreach ($controllerDirs as $moduleId => $cDir) {
            $actions[$moduleId][$cDir] = $this->actionGetcontrollersandactions($cDir);
        }

        $action_lists = $actions[0][\Yii::getAlias('@backend/controllers')];
        if ($actionsmodule != NULL) {
            $action_lists += $actionsmodule;
        }

        if ($action_lists != NULL) {
            foreach ($action_lists as $key => $action_list) {
                if ($action_list != NULL) {

                    foreach ($action_list as $actions) {
                        $check_avail = \common\models\AdminRoleList::find()->where(['action' => $actions, 'controller' => $key])->one();
                        if ($check_avail == NULL) {
                            $name = strtolower(preg_replace('~(?=[A-Z])(?!\A)~', ' ', $key));
                            $add_list = new \common\models\AdminRoleList();
                            $add_list->action = $actions;
                            $add_list->controller = $key;
                            $add_list->name = $name;
                            $add_list->status = 1;
                            $add_list->created_at = date('Y-m-d H:i:s');
                            $add_list->created_by = Yii::$app->user->identity->id;
                            $add_list->updated_by = Yii::$app->user->identity->id;
                            $add_list->save(FALSE);
                        }
                    }
                }
            }
        }
        $searchModel = new AdminRoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdminRole model.
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
     * Creates a new AdminRole model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $model = new AdminRole();
        $role = new \common\models\AdminRoleLocation();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {

                $get_location = $_POST['AdminRoleLocation']['role_list_id'];
                $get_location = array_filter($get_location);
                if ($get_location != NULL) {
                    foreach ($get_location as $get_loc) {
                        $get_role = \common\models\AdminRoleList::find()->where(['id' => $get_loc])->one();
                        if ($get_role != NULL) {
                            $role_cat = new \common\models\AdminRoleLocation();
                            $role_cat->role_id = $model->id;
                            $role_cat->role_list_id = $get_role->id;
                            $role_cat->created_at = date('Y-m-d H:i:s');
                            $role_cat->created_by = Yii::$app->user->identity->id;
                            $role_cat->updated_by = Yii::$app->user->identity->id;
                            $role_cat->status = 1;
                            $role_cat->save(false);
                        }
                    }
                }
                Yii::$app->session->setFlash('success', "Data created successfully.");
                return $this->redirect(['index']);
            }
        }


        return $this->render('create', [
                    'model' => $model,
                    'role' => $role,
        ]);
    }

    /**
     * Updates an existing AdminRole model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $role = new \common\models\AdminRoleLocation();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {

//                $check_role_cat_exist = \common\models\AdminRoleLocation::find()->where(['status' => 1, 'role_id' => $model->id])->all();
//
//                if ($check_role_cat_exist != NULL) {
//                    foreach ($check_role_cat_exist as $check) {
//                        $check->delete();
//                    }
//                }
                Yii::$app->db->createCommand()
                        ->update('admin_role_location', ['status' => 0], 'role_id = ' . $model->id)
                        ->execute();
                $get_location = $_POST['AdminRoleLocation']['role_list_id'];
                $get_location = array_filter($get_location);
                if ($get_location != NULL) {
                    foreach ($get_location as $get_loc) {
//                        if(isset($get_loc) )

                        $get_role = \common\models\AdminRoleList::find()->where(['id' => $get_loc])->one();
                        if ($get_role != NULL) {


                            $role_cat = new \common\models\AdminRoleLocation();
                            $check_role_cat_exist = \common\models\AdminRoleLocation::find()->where(['role_list_id' => $get_role->id, 'role_id' => $model->id])->one();
                            if ($check_role_cat_exist != NULL) {
                                $role_cat = $check_role_cat_exist;
                            }
                            $role_cat->role_id = $model->id;
                            $role_cat->role_list_id = $get_role->id;
                            $role_cat->created_at = date('Y-m-d H:i:s');
                            $role_cat->created_by = Yii::$app->user->identity->id;
                            $role_cat->updated_by = Yii::$app->user->identity->id;
                            $role_cat->status = 1;
                            $role_cat->save(false);
                        }
                    }
                }

                Yii::$app->session->setFlash('success', "Data updated successfully.");
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'role' => $role,
        ]);
    }

    /**
     * Deletes an existing AdminRole model.
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
     * Finds the AdminRole model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminRole the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = AdminRole::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
