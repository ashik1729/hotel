<?php

namespace backend\controllers;

use Yii;
use common\models\VisaOption;
    use common\models\VisaOptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
* VisaOptionController implements the CRUD actions for VisaOption model.
*/
class VisaOptionController extends Controller
{
/**
* {@inheritdoc}
*/
public function behaviors()
{

$tbl_name = 'VisaOption';
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
* Lists all VisaOption models.
* @return mixed
*/
public function actionIndex()
{
    $searchModel = new VisaOptionSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    ]);
}

/**
* Displays a single VisaOption model.
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
* Creates a new VisaOption model.
* If creation is successful, the browser will be redirected to the 'view' page.
* @return mixed
*/
public function actionCreate()
{
$model = new VisaOption();

if ($model->load(Yii::$app->request->post()) && $model->save()) {
return $this->redirect(['view', 'id' => $model->id]);
}

return $this->render('create', [
'model' => $model,
]);
}

/**
* Updates an existing VisaOption model.
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
* Deletes an existing VisaOption model.
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

/**
* Finds the VisaOption model based on its primary key value.
* If the model is not found, a 404 HTTP exception will be thrown.
* @param integer $id
* @return VisaOption the loaded model
* @throws NotFoundHttpException if the model cannot be found
*/
protected function findModel($id)
{
if (($model = VisaOption::findOne($id)) !== null) {
return $model;
}

throw new NotFoundHttpException('The requested page does not exist.');
}
}
