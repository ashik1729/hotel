<?php

namespace backend\modules\products\controllers;

use Yii;
use common\models\PackagesDate;
use common\models\PackagesDateSearch;
use common\models\PackagesPrice;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PackagesDateController implements the CRUD actions for PackagesDate model.
 */
class PackagesDateController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {

        $tbl_name = 'PackagesDate';
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
     * Lists all PackagesDate models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new PackagesDateSearch();


        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PackagesDate model.
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
     * Creates a new PackagesDate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PackagesDate();
        $pkg_price   = new PackagesPrice();
        $package_id  = 0;


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model'        => $model,
            'pkg_price'    => $pkg_price,
            'package_id'   => $package_id
        ]);
    }

    /**
     * Updates an existing PackagesDate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PackagesDate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSavePackageDatePrice()
    {
        $model        = new PackagesDate();
        $searchModel  = new PackagesDateSearch();
        $pkg_price    = new PackagesPrice();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $package_id = isset($_GET['id']) ? $_GET['id'] : 0;
        $log = [];
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_GET['id'])) {
                $model->package_id  = $package_id;
            }
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                $pkg_date_id        = $model->id;
                $package_details    = Yii::$app->request->post();
                $package_data = $package_details['PackagesPrice'];
                if (isset($package_data['min_person'])) {
                    $min_pern_data = $package_data['min_person'];
                    $max_per_data  = isset($package_data['max_person']) ? $package_data['max_person'] : "";
                    $price         = isset($package_data['price']) ? $package_data['price'] : "";

                    for ($i = 0; $i < count($min_pern_data); $i++) {
                        $pkg_price    = new PackagesPrice();
                        $pkg_price->package_id      = $package_id;
                        $pkg_price->package_date_id = $pkg_date_id;
                        $pkg_price->min_person  = isset($min_pern_data[$i]) ? $min_pern_data[$i] : "";
                        $pkg_price->max_person  = isset($max_per_data[$i]) ? $max_per_data[$i] : "";
                        $pkg_price->price       = isset($price[$i]) ? $price[$i] : "";
                        $pkg_price->created_at  = date('Y-m-d H:i:s');
                        $pkg_price->updated_at  = date('Y-m-d H:i:s');
                        if ($pkg_price->save()) {
                        }
                    }
                }

                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
                ]);
            } else {
                echo '<pre/>';
                print_r($model->errors);
                exit;
            }
        }
        return $this->render('create', [
            'model'        => $model,
            'pkg_price'    => $pkg_price,
            'package_id'   => $package_id
        ]);
    }

    /**
     * Finds the PackagesDate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PackagesDate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PackagesDate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
