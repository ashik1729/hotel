<?php

namespace backend\modules\users\controllers;

use Yii;
use common\models\Merchant;
use common\models\MerchantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * MerchantController implements the CRUD actions for Merchant model.
 */
class MerchantController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'Merchant';
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
//        print_r($action);
//        exit;
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
     * Getting the available button
     * @return mixed
     */
    public function actionManageSlots() {
        $request = Yii::$app->request;
        if ($request->isAjax) {

            $week_day_id = $_POST['week_day_id'];
            $merchant_id = $_POST['merchant_id'];
            $week_day = $_POST['week_day'];
            $week_day_availability = $_POST['week_day_availability'];
            $week_day_available_from = $_POST['week_day_available_from'];
            $week_day_available_to = $_POST['week_day_available_to'];
            $week_day_interval = $_POST['week_day_interval'];

            $slotsmodel = new \common\models\WeekDaysAvailability();
            $get_slotmodel = \common\models\WeekDaysAvailability::find()->where(['merchant_id' => $merchant_id]);
            if (isset($week_day_id) && $week_day_id != "") {
                $get_slotmodel->andWhere(['id' => $week_day_id]);
            }
            if (isset($week_day) && $week_day != "") {
                $get_slotmodel->andWhere(['day' => $week_day]);
            }
            if (isset($_POST['week_date']) && $_POST['week_date'] != "") {
                $get_slotmodel->andWhere(['date' => $_POST['week_date']]);
            }
            if ($get_slotmodel->one() != NULL) {
                $slotsmodel = $get_slotmodel->one();
            } else {
                $slotsmodel->created_by = yii::$app->user->identity->id;
                $slotsmodel->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            }
            $slotsmodel->availability = $week_day_availability;
            $slotsmodel->day = $week_day;
            $slotsmodel->merchant_id = $merchant_id;
            $slotsmodel->available_from = $week_day_available_from;
            $slotsmodel->available_to = $week_day_available_to;
            $slotsmodel->slot_interval = $week_day_interval;
            $slotsmodel->updated_by = yii::$app->user->identity->id;
            $slotsmodel->updated_by_type = 1;
            $slotsmodel->status = 1;
            if (isset($_POST['week_date']) && $_POST['week_date'] != '') {
                $slotsmodel->date = $_POST['week_date'];
            }
            $data = '<div class="col-12"><h6 class="text-center">No Time Slot Defined for this day</h6></div>';

            if ($slotsmodel->save()) {

                $get_disable_slots = \common\models\DisableSlots::find()->where("slot_from >= '" . $week_day_available_from . "' AND slot_to <= '" . $week_day_available_to . "'")->andWhere(['merchant_id' => $merchant_id, 'day' => $slotsmodel->id])->all();
                if ($week_day_availability == 1) {
                    $data = $this->renderPartial('_get_slots', [
                        'disable_slots' => $get_disable_slots,
                        'available_from' => $week_day_available_from,
                        'available_to' => $week_day_available_to,
                        'interval' => $week_day_interval,
                        'get_slotmodel' => $slotsmodel,
                    ]);
                }
                $array['status'] = 200;
                $array['error'] = '';
                $array['message'] = $data;
            } else {
                $errors = $slotsmodel->getErrors();
                $err = [];
                foreach ($errors as $error) {
                    $err[] = $error[0];
                }
                $array['status'] = 411;
                $array['error'] = $err;
                $array['message'] = $data;
            }


            echo json_encode($array);
            exit;
        }
    }

    public function actionChangeSlot() {
        $request = Yii::$app->request;
        if ($request->isAjax) {

            $day = $_POST['day'];
            $slot = $_POST['slot'];
            $merchant_id = $_POST['merchant_id'];
            $status = $_POST['status'];
            $slots = explode(" - ", $slot);

            $slot_from = date('H:i', strtotime($slots[0]));
            $slot_to = date('H:i', strtotime($slots[1]));

            $slotsmodel = new \common\models\DisableSlots();
            if (isset($day) && $day != "") {
                $get_slotmodel = \common\models\DisableSlots::find()->where(['merchant_id' => $merchant_id, 'slot_from' => $slot_from, 'slot_to' => $slot_to])->one();

                if ($get_slotmodel != NULL) {
                    $slotsmodel = $get_slotmodel;
                } else {
                    $slotsmodel->created_by = yii::$app->user->identity->id;
                    $slotsmodel->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                }
            }
            if (isset($_POST['date']) && $_POST['date'] != "") {
                $slotsmodel->date = $_POST['date'];
            }
            $slotsmodel->day = $day;
            $slotsmodel->merchant_id = $merchant_id;
            $slotsmodel->slot_from = $slot_from;
            $slotsmodel->slot_to = $slot_to;
            $slotsmodel->updated_by = yii::$app->user->identity->id;
            $slotsmodel->updated_by_type = 1;
            $slotsmodel->status = $status;
            $data = '';
            if ($slotsmodel->save()) {
                $array['status'] = 200;
                $array['error'] = '';
                $array['message'] = $data;
            } else {
                $errors = $slotsmodel->getErrors();
                $err = [];
                foreach ($errors as $error) {
                    $err[] = $error[0];
                }
                $array['status'] = 411;
                $array['error'] = $err;
                $array['message'] = $data;
            }


            echo json_encode($array);
            exit;
        }
    }

    /**
     * Lists all Merchant models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MerchantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Merchant model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {

        if (Yii::$app->user->identity->interface == 'merchant') {
            if ($id != Yii::$app->user->identity->id) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Merchant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Merchant();
        $weekdaymodel = new \common\models\WeekDaysAvailability();
        $shippingModel = new \common\models\MerchantShipmentMethods();
        if ($model->load(Yii::$app->request->post())) {
            $password = $model->password;
            $model->password = Yii::$app->security->generatePasswordHash($password);
            $model->generateAuthKey();
            $file = UploadedFile::getInstance($model, 'profile_image');
            $signature = UploadedFile::getInstance($model, 'signature');
            $logo = UploadedFile::getInstance($model, 'business_logo');
            $gallery = UploadedFile::getInstances($model, 'business_gallery');
            $name = md5(microtime());
            $profile_name = 'profile' . $name;
            $signature_name = 'signature' . $name;
            $logo_name = 'logo' . $name;
            if ($file) {
                $model->profile_image = $profile_name . '.' . $file->extension;
            }
            if ($signature) {
                $model->signature = $signature_name . '.' . $signature->extension;
            }
            if ($logo) {
                $model->business_logo = $logo_name . '.' . $logo->extension;
            }
            if (isset($model->category) && $model->category != '' && $model->category != NULL) {
                $model->category = implode(',', $model->category);
            }
            if (isset($_POST['search_tag']) && $_POST['search_tag'] != NULL) {
                $model->search_tag = implode(',', $_POST['search_tag']);
            }
            $model->business_gallery = "";
            $model->status = 10;
            $model->availability = 1;
            $model->email = $model->email;
            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $model->role = 3;
            $model->updated_by_type = 1;
            $model->interface = "merchant";
            $explocation = explode(',', $model->location);
            if ($explocation != NULL && count($explocation) == 2) {
                $model->latitude = $explocation[0];
                $model->longitude = $explocation[1];
            }
            if ($model->save()) {
                if ($file) {
                    $model->uploadFile($file, $profile_name, 'merchant/' . $model->id . '/profile');
                }
                if ($signature) {
                    $model->uploadFile($signature, $signature_name, 'merchant/' . $model->id . '/signature');
                }
                if ($logo) {
                    $model->uploadFile($logo, $logo_name, 'merchant/' . $model->id . '/logo');
                }
                if ($gallery != NULL) {
                    $model->uploadMultipleImage($gallery, $model->id, $name, 'merchant/' . $model->id . '/gallery');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                print_r($model->errors);
                exit;
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'weekdaymodel' => $weekdaymodel,
                    'shippingModel' => $shippingModel,
        ]);
    }

    /**
     * Updates an existing Merchant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        if (Yii::$app->user->identity->interface == 'merchant') {
            if ($id != Yii::$app->user->identity->id) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
        $weekdaymodel = new \common\models\WeekDaysAvailability();
        $shippingModel = new \common\models\MerchantShipmentMethods();
        $model = $this->findModel($id);
        $current_status = $model->status;
        $images = $model->profile_image;
        $old_signature = $model->signature;
        $logos = $model->business_logo;
        $gallery_data = $model->business_gallery;
        $old_password = $model->password;
        $model->scenario = "update_merchant";
        if ($model->load(Yii::$app->request->post())) {
            if (isset($_POST['search_tag']) && $_POST['search_tag'] != NULL) {
                $model->search_tag = implode(',', $_POST['search_tag']);
            }
            if ($old_password != $model->password) {
                $model->setPassword($model->password);
            }
            if (isset($model->category) && $model->category != '' && $model->category != NULL) {
                $model->category = implode(',', $model->category);
            }
//            $model->generateAuthKey();
            $file = UploadedFile::getInstance($model, 'profile_image');
            $signature = UploadedFile::getInstance($model, 'signature');
            $logo = UploadedFile::getInstance($model, 'business_logo');
            $gallery = UploadedFile::getInstances($model, 'business_gallery');
            $name = md5(microtime());
            $profile_name = 'profile' . $name;
            $signature_name = 'signature' . $name;
            $logo_name = 'logo' . $name;
            if ($file) {
                $model->profile_image = $profile_name . '.' . $file->extension;
            } else {
                $model->profile_image = $images;
            }
            if ($signature) {
                $model->signature = $signature_name . '.' . $signature->extension;
            } else {
                $model->signature = $old_signature;
            }
            if ($logo) {
                $model->business_logo = $logo_name . '.' . $logo->extension;
            } else {
                $model->business_logo = $logos;
            }
            $model->updated_by = yii::$app->user->identity->id;
            $model->updated_by_type = 1;
            $model->role = 3;
            $model->interface = "merchant";
            if ($gallery_data == '') {
                $model->business_gallery = "";
            } else {
                $model->business_gallery = $gallery_data;
            }
            $explocation = explode(',', $model->location);
            if ($explocation != NULL && count($explocation) == 2) {
                $model->latitude = $explocation[0];
                $model->longitude = $explocation[1];
            }
            if ($model->save()) {
                $post_status = $model->status;
//                if ($current_status != $post_status && $current_status == 0 && $post_status == 10) {
                $getUsers = \common\models\User::find()->where(['status' => 10, 'user_type' => 1])->all();

                if ($getUsers != NULL) {
                    $userId = array_column($getUsers, 'id');
                    $getCategory = explode(',', $model->category);
                    $catEn = [];
                    $catAr = [];
                    if ($getCategory != NULL) {

                        foreach ($getCategory as $getCat) {
                            $category = \common\models\MerchantCategory::findOne(['id' => $getCat]);
                            if ($category != NULL) {
                                $catEn[] = $category->name;
                                $catAr[] = $category->name_ar;
                            }
                        }
                    }
                    $categoryEn = implode(',', $catEn);
                    $categoryAr = implode(',', $catAr);
                    $template_key["{%partner_name%}"] = $model->business_name;
                    $template_key["{%partner_name_ar%}"] = $model->business_name_ar;
                    $template_key["{%partner_category%}"] = $categoryEn;
                    $template_key["{%partner_category_ar%}"] = $categoryAr;
                    $titleEn = $this->getMessage("partner_added_title", 1);
                    $titleAr = $this->getMessage("partner_added_title", 2);
                    $bodyEn = $this->getBody("partner_added_desc", $template_key, 1);
                    $bodyAr = $this->getBody("partner_added_desc", $template_key, 2);
                    $notif_key['type'] = 3;
                    $notif_key['redirection'] = "NEW_PARTNERS";
                    $data = [
                        "title" => [
                            "en" => $titleEn,
                            "ar" => $titleAr
                        ],
                        "description" => [
                            "en" => $bodyEn,
                            "ar" => $bodyAr
                        ],
                        "reciever_type" => 1,
                        "redirection_id" => NULL,
                        "notification_type" => 3,
                        "notif_key" => $notif_key,
                        "marketing_image" => "",
                        "reciever" => $userId,
                    ];
// $courier_notifications = Yii::$app->notificationManager->marknotifications($type, $reciever, $title, $title_ar, $reciever_type, $desc, $desc_ar, $notif_key, $marketing_image);
                    $saveNotifications = Yii::$app->NotificationManager->savenotifications($data);
                    $result = Yii::$app->NotificationManager->pushnotification($userId, $titleEn, $titleAr, $bodyEn, $bodyAr, $notif_key);
                }
//                }
                $shipp_error = [];

                if (isset($_POST['MerchantShipmentMethods'])) {
                    if ($_POST['MerchantShipmentMethods']['shippment_id']) {
                        if (count($_POST['MerchantShipmentMethods']['shippment_id']) > 0) {
                            for ($i = 0; $i < count($_POST['MerchantShipmentMethods']['shippment_id']); $i++) {
                                $checkExist = \common\models\MerchantShipmentMethods::find()->where(['merchant_id' => $model->id, 'shippment_id' => $_POST['MerchantShipmentMethods']['shippment_id'][$i]])->one();
                                if ($checkExist != NULL) {
                                    $merchantShiping = $checkExist;
                                } else {
                                    $merchantShiping = new \common\models\MerchantShipmentMethods();
                                }
                                $merchantShiping->merchant_id = $model->id;
                                $merchantShiping->shippment_id = $_POST['MerchantShipmentMethods']['shippment_id'][$i];
                                $merchantShiping->price = $_POST['MerchantShipmentMethods']['price'][$i];
                                $merchantShiping->information = $_POST['MerchantShipmentMethods']['information'][$i];
                                $merchantShiping->status = $_POST['MerchantShipmentMethods']['status'][$i];
                                $merchantShiping->defaultShipment = $_POST['MerchantShipmentMethods']['defaultShipment'][$i];
                                $merchantShiping->created_by = yii::$app->user->identity->id;
                                $merchantShiping->updated_by = yii::$app->user->identity->id;
                                $merchantShiping->created_by_type = 2; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                                $merchantShiping->updated_by_type = 2;
                                if ($merchantShiping->save()) {
                                    if ($_POST['MerchantShipmentMethods']['defaultShipment'][$i] == 1) {
                                        \common\models\MerchantShipmentMethods::updateAll(['defaultShipment' => 0], ['and', ['merchant_id' => $merchantShiping->merchant_id], ['!=', 'id', $merchantShiping->id]]);
                                    }
                                } else {
                                    $shipp_error[] = $merchantShiping->errors;
                                }
                            }
                        }
                    }
                }

                if ($file) {
                    $model->uploadFile($file, $profile_name, 'merchant/' . $model->id . '/profile');
                }
                if ($signature) {
                    $model->uploadFile($signature, $signature_name, 'merchant/' . $model->id . '/signature');
                }
                if ($logo) {
                    $model->uploadFile($logo, $logo_name, 'merchant/' . $model->id . '/logo');
                }
                if ($gallery != NULL) {
                    $model->uploadMultipleImage($gallery, $model->id, $name, 'merchant/' . $model->id . '/gallery');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                print_r($model->errors);
                exit;
            }
        }
        return $this->render('update', [
                    'model' => $model,
                    'weekdaymodel' => $weekdaymodel,
                    'shippingModel' => $shippingModel,
        ]);
    }

    /**
     * Deletes an existing Merchant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    function getMessage($message, $lang) {
        $get_message = \common\models\MobileStrings::find()->where([
                    'string_key' => $message])->one();

        if ($get_message != NULL) {

            if ($lang == 2) {

                return $get_message->string_ar;
            } else {
                return$get_message->string_en;
            }
        } else {
            return "";
        }
    }

    function getBody($desc_key, $template_key = [], $lang) {

        $body = $this->getMessage($desc_key, $lang);
        if ($template_key != NULL) {

            foreach ($template_key as $key => $val) {
                $body = str_replace($key, $val, $body);
            }
        } return $body;
    }

    public function actionDeleteWeekDay($id) {

        $request = Yii::$app->request;
        if ($request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();

            if (($model = \common\models\WeekDaysAvailability::findOne($id)) !== null) {
                if ($model->delete()) {
                    (new \yii\db\Query)
                            ->createCommand()
                            ->delete('disable_slots', ['day' => $id])
                            ->execute();
//                    if (\common\models\DisableSlots::deleteAll(['and', ['days' => $id]])) {
                    $transaction->commit();
                    $array['status'] = 200;
                    $array['error'] = '';
                    $array['message'] = "";
//                    } else {
//                        $transaction->rollBack();
//                        $array['status'] = 412;
//                        $array['error'] = '';
//                        $array['message'] = "";
//                    }
                } else {
                    $transaction->rollBack();
                    $array['status'] = 413;
                    $array['error'] = $model->errors;
                    $array['message'] = "";
                }
            } else {
                $array['status'] = 414;
                $array['error'] = "";
                $array['message'] = "No Data Available";
            }

            echo json_encode($array);
            exit;
        }
    }

    public function actionGalleryDelete() {
        $image = $_GET['item'];
        $id = $_GET['id'];
        $model = $this->findModel($id);

        if (is_dir(Yii::$app->basePath . '/../uploads/merchant/' . $model->id . '/gallery')) {
            chmod(Yii::$app->basePath . '/../uploads/merchant/' . $model->id . '/gallery', 0777);

            $data = Yii::$app->basePath . '/../uploads/merchant/' . $model->id . '/gallery/' . $image;
            if (file_exists($data)) {
                chmod($data, 0777);
                unlink($data);
            }

            $gallery = explode(', ', $model->business_gallery);
            $array1 = Array($image);
            $array3 = array_diff($gallery, $array1);
            $model->business_gallery = implode(', ', $array3);
            $model->save(FALSE);


            Yii::$app->session->setFlash('success', "Business Gallery image deleted successfully.");
            $this->redirect(array('merchant/update?id = ' . $id));
        }
    }

    /**
     * Finds the Merchant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Merchant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Merchant::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
