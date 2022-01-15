<?php

namespace backend\modules\marketing\controllers;

use Yii;
use common\models\MarketingNotification;
use common\models\MarketingNotificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * MarketingNotificationController implements the CRUD actions for MarketingNotification model.
 */
class MarketingNotificationController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'MarketingNotification';
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
     * Lists all MarketingNotification models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MarketingNotificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MarketingNotification model.
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
     * Creates a new MarketingNotification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MarketingNotification();

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file');
            if ($file) {
                $model->file = $file->extension;
            }
            $name = md5(microtime());
            $image_name = 'notification' . $name;
            if ($file) {
                $model->file = $image_name . '.' . $file->extension;
            }


            $user_data = [];
            if (isset($_POST['MarketingNotification']['user_group']) && $_POST['MarketingNotification']['user_group'] != '') {
                $get_group = \common\models\UserGroup::findOne(['id' => $_POST['MarketingNotification']['user_group']]);
                if ($get_group != NULL) {
                    if ($get_group->can_name == "ALL_USERS") {
                        $get_all_user = \common\models\User::find()->where(['status' => 10])->andWhere('user_type =1')->asArray()->all();
                        if ($get_all_user != NULL) {
                            $user_data = array_unique(array_column($get_all_user, 'id'));
                        }
                        $user_data[] = -1;
                    } else if ($get_group->can_name == "NEWSLETTER_USER") {
                        $user_data = $_POST['MarketingNotification']['user'];
                    } else if ($get_group->can_name == "PURCHASE_USER") {
                        $user_data = $_POST['MarketingNotification']['user'];
                    } else if ($get_group->can_name == "TARGETED_USER") {
                        $user_data = $_POST['MarketingNotification']['user'];
                    }
                    if ($_POST['MarketingNotification']['user'] != NULL) {
                        $model->user = implode(',', $_POST['MarketingNotification']['user']);
                    } else {
                        $model->user = "-1";
                    }
                }

                $transaction = Yii::$app->db->beginTransaction();

                if ($model->save()) {

                    $marketing_image = '';
                    if ($file) {
                        $model->upload($file, $image_name, 'thumb');
                        $marketing_image = $model->file;
                    }
                    $notif_key['type'] = 1;
                    $notif_key['redirection'] = "PROMOTION_AND_DISCOUNT";
                    $data = [
                        "title" => [
                            "en" => $_POST['MarketingNotification']['title_en'],
                            "ar" => $_POST['MarketingNotification']['title_ar']
                        ],
                        "description" => [
                            "en" => $_POST['MarketingNotification']['description_en'],
                            "ar" => $_POST['MarketingNotification']['description_ar']
                        ],
                        "reciever_type" => 1,
                        "redirection_id" => NULL,
                        "notification_type" => $model->notification_type,
                        "notif_key" => $notif_key,
                        "marketing_image" => $marketing_image,
                        "reciever" => $user_data,
                    ];
// $courier_notifications = Yii::$app->notificationManager->marknotifications($type, $reciever, $title, $title_ar, $reciever_type, $desc, $desc_ar, $notif_key, $marketing_image);
                    $saveNotifications = Yii::$app->NotificationManager->savenotifications($data);
                    if ($saveNotifications == NULL) {
                        $transaction->commit();
                    } else {
                        $transaction->rollBack();
                    }

                    if (!is_dir(Yii::$app->basePath . '/../uploads/marketing-notification')) {
                        mkdir(Yii::$app->basePath . '/../uploads/marketing-notification');
                        chmod(Yii::$app->basePath . '/../uploads/marketing-notification' . '', 0777);
                    }

                    $result = Yii::$app->NotificationManager->pushnotification($user_data, $_POST['MarketingNotification']['title_en'], $_POST['MarketingNotification']['title_ar'], $_POST['MarketingNotification']['description_en'], $_POST['MarketingNotification']['description_ar'], $notif_key);

//                    $tokens = [];
//                    $tokens_ios = [];
//                    $tokens_ar = [];
//                    $tokens_ar_ios = [];
//                    $fbData = [];
//                    if ($user_data != NULL) {
//                        // echo '<pre/>';
//                        foreach ($user_data as $user_dt) {
//                            $get_device = \common\models\Authentication::find()->where(['status' => 1, 'user_id' => $user_dt])->all();
//                            if ($get_device != NULL) {
//                                foreach ($get_device as $get_d) {
//                                    $check_user = \common\models\User::find()->where(['id' => $get_d->user_id, 'status' => 10])->one();
//                                    if ($check_user != NULL) {
//
//                                        $lang = $check_user->app_lang_id;
//                                        $check_desable = \common\models\UserNotification::find()->where(['user_id' => $get_d->user_id, 'notification_type' => $_POST['MarketingNotification']['notification_type'], 'status' => 0])->one();
//                                        if ($check_desable == NULL) {
//                                            if ($lang == 1) {
//                                                if ($get_d->device_type == 1) {
//                                                    $fbData['token']['en']['android'][] = $get_d->fb_token;
//                                                } else {
//                                                    $fbData['token']['en']['ios'][] = $get_d->fb_token;
//                                                }
//                                            } else {
//                                                if ($get_d->device_type == 1) {
//                                                    $fbData['token']['ar']['android'][] = $get_d->fb_token;
//                                                } else {
//                                                    $fbData['token']['ar']['ios'][] = $get_d->fb_token;
//                                                }
//                                            }
//                                        }
//                                    }
//                                }
//                            }
//                        }
//                    }
//                    $notif_key['image'] = 'uploads/marketing-notification/' . $model->file;
//                    $notif_key['redirection'] = 'MARKETING_VISUALIZER';
//
//                    $fbData['token']['en']['android'] = $this->getFilterToken($fbData['token']['en']['android']);
//                    $fbData['token']['en']['ios'] = $this->getFilterToken($fbData['token']['en']['ios']);
//                    $fbData['token']['ar']['android'] = $this->getFilterToken($fbData['token']['ar']['android']);
//                    $fbData['token']['ar']['ios'] = $this->getFilterToken($fbData['token']['ar']['ios']);
//                    $fbData['title']['ar'] = $model->title_ar;
//                    $fbData['title']['en'] = $model->title_en;
//                    $fbData['body']['ar'] = $model->description_ar;
//                    $fbData['body']['en'] = $model->description_en;
//                    $fbData['notif_key'] = $notif_key;
//
//
//
//                    $newtk = [];
//                    $result = [];
//                    foreach (['en', 'ar'] as $lang) {
//                        foreach (['android', 'ios'] as $app) {
//                            if ($fbData['token'][$lang][$app] != NULL) {
//                                foreach ($fbData['token'][$lang][$app] as $tok) {
//                                    $newtk [] = $tok;
//                                }
//                                if ($newtk != NULL) {
//                                    $sendData = Yii::$app->NotificationManager->sendnotification($newtk, $fbData['title'][$lang], $fbData['body'][$lang], $fbData['notif_key'], $app);
//                                    $result['token'][$lang][$app] = $newtk;
//                                    $result['result'][$lang][$app] = $sendData;
//                                }
//                            }
//                        }
//                    }

                    Yii::$app->session->setFlash('success', "Data created successfully.");
                    $code = 200;
                    $name = "marketing Notification";
                    $file_size = filesize(Yii ::$app->basePath . "/../uploads/logs/marketing/marketing_notification_log.txt");
                    $size = $file_size / 1000;
                    if ($size >= 1000) {
                        $old_name = Yii::$app->basePath . "/../uploads/logs/marketing/marketing_notification_log.txt";
                        $new_name = Yii::$app->basePath . "/../uploads/logs/marketing/marketing_notification_log" . date('Y-m-d H:i:s') . ".txt";
                        rename($old_name, $new_name);
                        $fp = fopen(Yii::$app->basePath . '/../uploads/logs/marketing/marketing_notification_log.txt', "a") or die("Unable to open file!");
                    } else {
                        $fp = fopen(Yii::$app->basePath . '/../uploads/logs/marketing/marketing_notification_log.txt', "a") or die("Unable to open file!");
                    }
                    if ($code != 200) {
                        $write_data = date('Y-m-d H:i:s A') . ' - ' . $name . ' - Error code : ' . $code;
                    } else {

                        $write_data = date('Y-m-d H:i:s A') . ' - ' . $name . ' - Success : ' . $code;
                    }
                    $imp = '';
                    if ($result != '') {
                        if (is_array($result)) {
                            $imp = json_encode($result);
                        } else {
                            $imp = $result;
                        }
                    }
                    fwrite($fp, "\r\n" . $write_data);
                    fwrite($fp, "\r\n" . $imp);
                    fclose($fp);
                } else {
                    print_r($model->errors);
                    exit;
                }
            }



            return $this->redirect(['index']);
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    function getFilterToken($tokens = []) {
        if ($tokens != NULL) {
            $tokens = array_filter($tokens);
            $tokens = array_unique($tokens);
            $tokens = array_values($tokens);
        }
        // print_r($tokens);

        return $tokens;
    }

    function getBody($desc_key, $template_key = [], $lang) {
        $body = $this->getMessage($desc_key, $lang);
        if ($template_key != NULL) {
            foreach ($template_key as $key => $val) {
                $body = str_replace(
                        $key, $val, $body);
            }
        }
        return $body;
    }

    /**
     * Updates an existing MarketingNotification model.
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
     * Deletes an existing MarketingNotification model.
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
     * Finds the MarketingNotification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MarketingNotification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MarketingNotification::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
