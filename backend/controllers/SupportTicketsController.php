<?php

namespace backend\controllers;

use Yii;
use common\models\SupportTickets;
use common\models\SupportTicketsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * SupportTicketsController implements the CRUD actions for SupportTickets model.
 */
class SupportTicketsController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'SupportTickets';
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
     * Lists all SupportTickets models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SupportTicketsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SupportTickets model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SupportTickets model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionGetOrderInfo() {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $data = '';
            $order_id = $_POST['order_id'];
            $get_data_query = \common\models\OrderProducts::find()->where(['order_id' => $order_id])->all();

            if ($get_data_query != NULL) {
                $data = '<option value="">Select Products</option>';
                foreach ($get_data_query as $get_data) {
                    $options = "";
                    $final_options = "";
                    if ($get_data->options != NULL && $get_data->options != "") {
                        $exp_get_option_datas = explode(',', $get_data->options);
                        if ($exp_get_option_datas != NULL) {
                            $get_option_datas = \common\models\AttributesValue::find()->where(['id' => $exp_get_option_datas])->all();
                            if ($get_option_datas != NULL) {
                                foreach ($get_option_datas as $get_option_data) {
                                    $options .= $get_option_data->attributes0->name . ':' . $get_option_data->value . ",";
                                }
                            }
                        }
                    }
                    if ($options != "") {
                        $final_options = "(" . $options . ")";
                    }
                    $data .= '<option value="' . $get_data->id . '">' . $get_data->product->product_name_en . $final_options . '</option>';
                }
            }
            echo $data;
        }
    }

    public function actionGetOrderData($q) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $result = [];
        $items = [];
        $orders = \common\models\Orders::find()->where(['like', 'id', $q])->all();
        if ($orders != NULL) {
            foreach ($orders as $order) {
                array_push($items, [
                    'id' => $order->id,
                    'text' => $order->user->first_name,
                    'email' => $order->user->email,
                    'date' => date('M d Y H:i A', strtotime($order->created_at))
                ]);
            }
        }
        $result['items'] = $items;

        return $result;
    }

    public function actionCreate() {
        $model = new SupportTickets();

        if ($model->load(Yii::$app->request->post())) {

            $order = \common\models\Orders::findOne(['id' => $model->order_id]);
            if ($order != NULL) {
                $transaction = Yii::$app->db->beginTransaction();
                $model->id = strtoupper(uniqid('DS'));
                $model->user_id = $order->user_id;
                $model->status = 1;
                $model->created_by = yii::$app->user->identity->id;
                $model->created_by_type = 2;
                $model->updated_by = yii::$app->user->identity->id;
                $model->updated_by_type = 2;
                $model->sort_order = 2;
                if ($model->save()) {

                    $chat_modal = new \common\models\SupportChat();
                    $file = UploadedFile::getInstance($model, 'file');
                    $name = md5(microtime());
                    $file_name = $name;
                    if ($file) {
                        $chat_modal->file = $name . '.' . $file->extension;
                    }
                    $chat_modal->id = strtoupper(uniqid('DC'));
                    $chat_modal->message = $_POST['SupportTickets']['message'];
                    $chat_modal->ticket_id = $model->id;
                    $chat_modal->sender = yii::$app->user->identity->id;
                    $chat_modal->reciever = $model->user_id;
                    $chat_modal->sender_type = 2;
                    $chat_modal->reciever_type = 1;
                    $chat_modal->status = 1;
                    $chat_modal->read_status = 1;
                    $chat_modal->created_by = yii::$app->user->identity->id;
                    $chat_modal->created_by_type = 2;
                    $chat_modal->updated_by = yii::$app->user->identity->id;
                    $chat_modal->updated_by_type = 2;
                    if ($chat_modal->save()) {
                        if ($file) {
                            if ($chat_modal->uploadFile($chat_modal->ticket_id, $file, $name)) {

                            }
                        }
                        $transaction->commit();
                        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } else {
                        $transaction->rollBack();
                        $errors_data = $chat_modal->getErrors();
                        foreach ($errors_data as $errors_dat) {
                            $errors[] = $errors_dat[0];
                        }
                        echo implode(',', $errors);
                        exit;
                    }
                } else {
                    $transaction->rollBack();
                    $errors_data = $model->getErrors();
                    foreach ($errors_data as $errors_dat) {
                        $errors[] = $errors_dat[0];
                    }
                    echo implode(',', $errors);
                    exit;
                }
            }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdateStatus($id) {
        $model = SupportTickets::findOne(['id' => $id]);
        if ($model != NULL) {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', "Ticket Updated.");
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdateChat($id) {
        $errors = [];
        if ($_FILES != NULL || $_POST != NULL) {
            $model = SupportTickets::findOne(['id' => $id]);
            if ($model != NULL) {
                //   $transaction = Yii::$app->db->beginTransaction();

                $chat_modal = new \common\models\SupportChat();
                $chat_modal->id = strtoupper(uniqid('DC'));
                $chat_modal->ticket_id = $id;
                $chat_modal->sender = yii::$app->user->identity->id;
                $chat_modal->reciever = $model->user_id;
                $chat_modal->sender_type = 2;
                $chat_modal->reciever_type = 1;
                $chat_modal->status = 1;
                $chat_modal->read_status = 1;
                $chat_modal->created_by = yii::$app->user->identity->id;
                $chat_modal->created_by_type = 2;
                $chat_modal->updated_by = yii::$app->user->identity->id;
                $chat_modal->updated_by_type = 2;
                if (isset($_POST['message']) && $_POST['message'] != "") {
                    $chat_modal->message = $_POST['message'];
                }
                if (isset($_FILES) && $_FILES['file'] != NULL) {
                    $file = UploadedFile::getInstanceByName('file');
                    $name = md5(microtime());
                    if ($file) {
                        $chat_modal->file = $name . '.' . $file->extension;
                    }
                }
                if ($chat_modal->save()) {
                    if ($file) {

                        if ($chat_modal->uploadFile($chat_modal->ticket_id, $file, $name)) {
                            //$transaction->commit();
                        }
                    }

                    // $transaction->commit();
                    echo 1;
                    exit;
//                    Yii::$app->session->setFlash('success', "Ticket Updated.");
//                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    $errors_data = $chat_modal->getErrors();
                    foreach ($errors_data as $errors_dat) {
                        $errors[] = $errors_dat[0];
                    }
                    echo implode(',', $errors);
                    exit;
                }
            }
            exit;
        } else {
            $transaction->rollBack();

            echo 0;
            exit;
        }

//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->render('create', [
//                    'model' => $model,
//        ]);
    }

    /**
     * Updates an existing SupportTickets model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
     * Deletes an existing SupportTickets model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SupportTickets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SupportTickets the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SupportTickets::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
