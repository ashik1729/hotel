<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\LoginForm;
use yii\web\Response;
use yii\helpers\Json;
use yii\filters\Cors;
use yii\web\UploadedFile;
use frontend\controllers\CrmController;

/**
 * Site controller
 */
class UserController extends Controller {

    public $enableCsrfValidation = false;

    public static function allowedDomains() {
        date_default_timezone_set('Asia/Qatar');
        return [
            '*', // star allows all domains
                // 'http://test1.example.com',
                // 'http://test2.example.com',
        ];
    }

    /**
     * @inheritdoc
     * behaviors - Managing Core Request
     */
    public function behaviors() {
//        date_default_timezone_set('Asia/Qatar');
        $behaviors = parent::behaviors();


        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                // restrict access to
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                // Allow only POST and PUT methods
                'Access-Control-Request-Headers' => ['*'],
                // Allow only headers 'X-Wsse'
                'Access-Control-Allow-Credentials' => true,
                // Allow OPTIONS caching
                'Access-Control-Max-Age' => 30,
            // Allow the X-Pagination-Current-Page header to be exposed to the browser.
            // 'Access-Control-Expose-Headers' => [],
            ]
        ];
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::className(),
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];
        return $behaviors;
    }

//    public function actions() {        date_default_timezone_set('Asia/Qatar');
//        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
//            'captcha' => [
//                'class' => 'yii\captcha\CaptchaAction',
//                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//            ],
//            'auth' => [
//                'class' => 'yii\authclient\AuthAction',
//                'successCallback' => [$this, 'successCallback'],
//            ],
//        ];
//    }
    // Initialaise the page with setting language
    public function init() {
//        date_default_timezone_set('Asia/Qatar');
        parent::init();
    }

//Register as User/Merchant based on email, facebook,gmail
    public function actionRegister() {
        header('Content-type:appalication/json'); //Header content set to json
        $name = "User Register"; // Name of the api
        $headers = Yii::$app->request->headers;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Set output format structure
        $model = new \common\models\User(); // create user instance
        $merchant = new \common\models\Merchant(); //create merchant instence
        $merchant->scenario = 'register_merchant'; // setting model scenario
        if (isset($headers['lang']) && $headers['lang'] != "") { //setting App language
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $errors = [];
        $json = file_get_contents('php://input'); // Getting Post Data
        $post = json_decode($json, true); // Converting into Array
        if (isset($headers['accessToken']) && $headers['accessToken'] != "") { // Chech the Access token is set or not
            if (Yii::$app->ManageRequest->validateAccessToken($headers['accessToken'])) { // Validate Acccess Token
                if (isset($post['full_name']) && $post['full_name'] != '') {
                    $full_name = $post['full_name'];
                } else {
                    $full_name = "";
                    $errors['full_name'] = $post['full_name'];
                }

                if (isset($post['user_type']) && $post['user_type'] != '' && ($post['user_type'] == 1 || $post['user_type'] == 2)) {   // User types (1-User,2-Merchant,3-Guest:Guest DOnt Have any registration)
                    $user_type = $post['user_type'];
                    $model->user_type = $user_type;
                } else {
                    $user_type = "";
                    $errors['user_type'] = $post['user_type'];
                }


                if (isset($post['account_type']) && $post['account_type'] != '') {  //Account Type : 1-Email,2-Facebook,3-Gmail
                    $account_type = $post['account_type'];
                    $model->account_type = $account_type;
                } else {
                    $account_type = "";
                    $errors['account_type'] = $post['account_type'];
                }
                if (isset($post['email']) && $post['email'] != '') {
                    $email = $post['email'];
                } else {
                    $email = "";
                    if ($account_type == 1) {
                        $errors['email'] = $post['email'];
                    }
                }
                if ($account_type == 2 || $account_type == 3) {  // Account type 2 or 3 require the external_account_id and no require the password
                    if (isset($post['external_account_id']) && $post['external_account_id'] != '') {
                        $external_account_id = $post['external_account_id'];
                        $model->external_account_id = $external_account_id;
                    } else {
                        $external_account_id = "";
                        $errors['external_account_id'] = $post['external_account_id'];
                    }
                }
                if ($account_type == 1) {  // For Account is 1 it is require the password
                    if (isset($post['password']) && $post['password'] != '') {
                        if (base64_decode($post['password'], true)) { //Checking the given password is base 64 encoded or not
                            $password = base64_decode($post['password']);
                        } else {
                            $password = "";
                            $errors['password'] = $post['password'];
                        }
                    } else {
                        $password = "";
                        $errors['password'] = $post['password'];
                    }
                } else {
                    $password = "";
                }
                $cr_no = "";
                $business_name = "";
                $type_of_business = "";
                if ($user_type == 1) {   // If User type is User
                    if ($account_type == 1) { //Account Type as Email
                        $model->scenario = 'register_user';  //setting Scenario
                    } else { //Account Type is either Facebook or gmail
                        $model->scenario = 'register_user_social';
                    }
                } else if ($user_type == 2) { // If User type is Merchant following fields are required
                    if ($account_type == 1) { //setting Scenario
                        $model->scenario = 'register_merchant';
                    } else { //Account Type is either Facebook or gmail
                        $model->scenario = 'register_merchant_social';
                    }
                    if (isset($post['cr_no']) && $post['cr_no'] != '') {  //CR no is Business related registration number
                        $cr_no = $post['cr_no'];
                    } else {
                        $cr_no = "";
                        $errors['cr_no'] = $post['cr_no'];
                    }
                    if (isset($post['business_name']) && $post['business_name'] != '') { // Merchant Business Name
                        $business_name = $post['business_name'];
                    } else {
                        $business_name = "";
                        $errors['business_name'] = $post['business_name'];
                    }
                    if (isset($post['type_of_business']) && $post['type_of_business'] != '') { // Merchant Business Type (Eg: Car wash, Electronic seller
                        $type_of_business = $post['type_of_business']; // Merchant Business Type (Eg: Car wash, Electronic seller
                        $check_business_exist = \common\models\BusinessCategory::find()->where(['status' => 1, 'id' => $type_of_business])->one();
                        if ($check_business_exist == NULL) {
                            $errors['type_of_business'] = $post['type_of_business'];
                        }
                    } else {
                        $type_of_business = "";
                        $errors['type_of_business'] = $post['type_of_business'];
                    }
                }
                if (isset($post['offers_discount_notification']) && ($post['offers_discount_notification'] == 1 || $post['offers_discount_notification'] == 0)) {    // $offers_discount_notification (1-Subscribe, 0-Unsubscribe)
                    $offers_discount_notification = $post['offers_discount_notification'];
                } else {
                    $offers_discount_notification = "";
                    $errors['offers_discount_notification'] = isset($post['offers_discount_notification']) ? $post['offers_discount_notification'] : "";
                }

                if (isset($post['device_id']) && $post['device_id'] != '') { // Device ID for perticular Device
                    $device_id = $post['device_id'];
                } else {
                    $device_id = "";
                    $errors['device_id'] = $post['device_id'];
                }
                if (isset($post['device_type']) && $post['device_type'] != '') { // Device Type is may (1-Android,2-IOS)
                    $device_type = $post['device_type'];
                } else {
                    $device_type = "";
                    $errors['device_type'] = $post['device_type'];
                }
                if (isset($post['fb_token']) && $post['fb_token'] != '') { //Firebase Token is for push notification through firebase
                    $fb_token = $post['fb_token'];
                } else {
                    $fb_token = "";
                    $errors['fb_token'] = $post['fb_token'];
                }
                $model->cr_no = $cr_no;
                $model->business_name = $business_name;
                $model->type_of_business = $type_of_business;
                $model->first_name = $full_name;
                $model->last_name = $full_name;
                $model->password = $password;
                $model->email = $email;
                $model->user_otp = rand(10001, 99999);
                $model->status = 10;
                $model->updated_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                $model->created_by_type = 1;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                $model->generateAuthKey();
                $model->app_lang_id = "1";
                $transaction = Yii::$app->db->beginTransaction();
                if ($errors == NULL) { // All Field is clear to save
                    if ($account_type == 2 || $account_type == 3) { // Account type is gmail or facebook
                        $get_user = \common\models\User::find()->where(['external_account_id' => $external_account_id])->one(); // check user is exist o not
                        if ($get_user != NULL) {

                            if ($get_user->status == 10) {
                                $user_auth = new \common\models\Authentication(); //Creating User session include authkey, refresh key, expiry time etc
                                $current_time = strtotime(date('Y-m-d H:i:s'));
                                $exp_time = strtotime('+24 hours', $current_time);
                                $check_auth_exist = \common\models\Authentication::find()->where(['user_id' => $get_user->id, 'device_id' => $device_id])->andWhere("expiry_time > '" . $current_time . "'")->one(); // Chekc there is a valid authenitcation instanse exist
                                if ($check_auth_exist == NULL) { // Not Exist Create one
                                    $user_auth->auth_key = Yii::$app->security->generateRandomString();
                                    $user_auth->refresh_token = bin2hex(random_bytes(25));
                                    $user_auth->expiry_time = $exp_time;
                                    $user_auth->user_id = $get_user->id;
                                    $user_auth->account_type_id = $get_user->user_type;
                                    $user_auth->status = 1;
                                    $user_auth->device_type = $device_type;
                                    $user_auth->device_id = $device_id;
                                    $user_auth->fb_token = $fb_token;
                                    $user_auth->save(FALSE);
                                } else { // Exist then use old one
                                    $user_auth = $check_auth_exist;
                                }
                                //Setting output params
                                $user['full_name'] = $get_user->first_name;
                                $user['user_id'] = $get_user->id;
                                $user['authToken'] = $user_auth->auth_key;
                                $user['refreshToken'] = $user_auth->refresh_token;
                                $user['email'] = $get_user->email;
                                $user['account_type'] = $get_user->account_type;
                                $user['expiry_time'] = (int) $user_auth->expiry_time;
                                $user['expiry_time_format'] = date('Y-m-d H:i:s', $user_auth->expiry_time);
                                $user['default_time_zone'] = $_SERVER['REQUEST_TIME'];
                                $user['time_zone'] = date_default_timezone_get();
                                $transaction->commit(); // Commit The Transaction
                                $array = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $user, 'authentication'); // Save the result to log return the output data
                                $get_message = Yii::$app->ManageRequest->getMessage('account_created_successfully', $lang); // changing the default success message to spesific message
                                $array['message'] = $get_message;
                                \Yii::$app->response->data = $array; // Output as json format
                                Yii::$app->end();
                            } else {
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(420, $name, $lang, $post, (object) [], 'authentication');
                                Yii::$app->end();
                            }
                        } else {
                            $exist = \common\models\User::find()->where(['email' => $email])->one(); // check a user exist with give email
                            if ($exist != NULL) {
                                $model = $exist;
                                $model->account_type = $account_type;
                                $model->external_account_id = $external_account_id;
                            }
                            if ($model->save()) {
                                $user_auth = new \common\models\Authentication(); //Creating User session include authkey, refresh key, expiry time etc
                                $current_time = strtotime(date('Y-m-d H:i:s'));
                                $exp_time = strtotime('+24 hours', $current_time);
                                $check_auth_exist = \common\models\Authentication::find()->where(['user_id' => $model->id, 'device_id' => $device_id])->andWhere("expiry_time > '" . $current_time . "'")->one(); // Chekc there is a valid authenitcation instanse exist
                                if ($check_auth_exist == NULL) { // Not Exist Create one
                                    $user_auth->auth_key = Yii::$app->security->generateRandomString();
                                    $user_auth->refresh_token = bin2hex(random_bytes(25));
                                    $user_auth->expiry_time = $exp_time;
                                    $user_auth->user_id = $model->id;
                                    $user_auth->account_type_id = $model->user_type;
                                    $user_auth->status = 1;
                                    $user_auth->device_type = $device_type;
                                    $user_auth->device_id = $device_id;
                                    $user_auth->fb_token = $fb_token;
                                    $user_auth->save(FALSE);
                                } else { // Exist then use old one
                                    $user_auth = $check_auth_exist;
                                }
                                //Setting output params
                                $user['full_name'] = $model->first_name;
                                $user['user_id'] = $model->id;
                                $user['authToken'] = $user_auth->auth_key;
                                $user['refreshToken'] = $user_auth->refresh_token;
                                $user['email'] = $model->email;
                                $user['account_type'] = $model->account_type;
                                $user['expiry_time'] = $user_auth->expiry_time;
                                $user['expiry_time_format'] = date('Y-m-d H:i:s', $user_auth->expiry_time);
                                $user['default_time_zone'] = $_SERVER['REQUEST_TIME'];
                                $user['time_zone'] = date_default_timezone_get();
                                $transaction->commit(); // Commit The Transaction
                                $array = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $user, 'authentication'); // Save the result to log return the output data
                                $get_message = Yii::$app->ManageRequest->getMessage('account_created_successfully', $lang); // changing the default success message to spesific message
                                $array['message'] = $get_message;
                                \Yii::$app->response->data = $array; // Output as json format
                                Yii::$app->end();
                            }
                        }
                    }
                    $exist = \common\models\User::find()->where(['email' => $email])->one(); // check a user exist with give email
                    if ($exist == NULL) { // exist
                        if ($model->validate()) { // Validate model rules
                            if ($model->save()) { // model going to save
                                if ($password != "" && $password != NULL) {
                                    $model->setPassword($password); // Setting hashed password
                                }
                                $model->updated_by = $model->id; // user side created by user itself
                                $model->created_by = $model->id; // user side updated by user itself
                                $model->save(FALSE); //updating model
                                if ($user_type == 2) { // if user is merchant need to create one merchant instance
                                    $merchant->user_id = $model->id;
                                    $merchant->first_name = $full_name;
                                    $merchant->last_name = $full_name;
                                    $merchant->password = Yii::$app->security->generatePasswordHash($password);
                                    $merchant->email = $email;
                                    $merchant->user_otp = rand(10001, 99999);
                                    $merchant->status = 1;
                                    $merchant->created_by = $model->id;
                                    $merchant->updated_by = $model->id;
                                    $merchant->updated_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                                    $merchant->created_by_type = 1;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                                    $merchant->generateAuthKey();
                                    $merchant->cr_no = $cr_no;
                                    $merchant->business_name = $business_name;
                                    $merchant->type_of_business = $type_of_business;
                                    $merchant->interface = "merchant"; // three interface we have(merchant,admin,franchise)

                                    if ($merchant->save()) { //save merchant instance
                                        if (Yii::$app->ManageRequest->getVariable('environment') == "S" || Yii::$app->ManageRequest->getVariable('environment') == "P") { // Project is on staging or in Prouction
                                            Yii::$app->MailRequest->sendConfirmation($model, $lang); // Sending Account create confirmation mail to user
                                        }
                                        $usernotification = new \common\models\UserNotification(); // Saving that the user want the notification later
                                        $usernotification->user_id = $model->id;
                                        $usernotification->notification_type = 1;
                                        $usernotification->status = $offers_discount_notification;
                                        $usernotification->save(FALSE);
                                        //Generating User Authentication Session
                                        $user_auth = new \common\models\Authentication(); //Creating User session include authkey, refresh key, expiry time etc
                                        $current_time = strtotime(date('Y-m-d H:i:s'));
                                        $exp_time = strtotime('+24 hours', $current_time);
                                        $check_auth_exist = \common\models\Authentication::find()->where(['user_id' => $model->id, 'device_id' => $device_id])->andWhere("expiry_time > '" . $current_time . "'")->one(); // Chekc there is a valid authenitcation instanse exist
                                        if ($check_auth_exist == NULL) { // Not Exist Create one
                                            $user_auth->auth_key = Yii::$app->security->generateRandomString();
                                            $user_auth->refresh_token = bin2hex(random_bytes(25));
                                            $user_auth->expiry_time = $exp_time;
                                            $user_auth->user_id = $model->id;
                                            $user_auth->account_type_id = $model->user_type;
                                            $user_auth->status = 1;
                                            $user_auth->device_type = $device_type;
                                            $user_auth->device_id = $device_id;
                                            $user_auth->fb_token = $fb_token;
                                            $user_auth->save(FALSE);
                                        } else { // Exist then use old one
                                            $user_auth = $check_auth_exist;
                                        }
                                        //Setting output params
                                        $user['full_name'] = $model->first_name;
                                        $user['user_id'] = $model->id;
                                        $user['authToken'] = $user_auth->auth_key;
                                        $user['refreshToken'] = $user_auth->refresh_token;
                                        $user['email'] = $model->email;
                                        $user['account_type'] = $model->account_type;
                                        $user['expiry_time'] = (int) $user_auth->expiry_time;
                                        $user['expiry_time_format'] = date('Y-m-d H:i:s', $user_auth->expiry_time);
                                        $user['default_time_zone'] = $_SERVER['REQUEST_TIME'];
                                        $user['time_zone'] = date_default_timezone_get();
                                        $transaction->commit(); // Commit The Transaction
                                        $array = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $user, 'authentication'); // Save the result to log return the output data
                                        $get_message = Yii::$app->ManageRequest->getMessage('account_created_successfully', $lang); // changing the default success message to spesific message
                                        $array['message'] = $get_message;
                                        \Yii::$app->response->data = $array; // Output as json format
                                    } else { //merchant can't able to save
                                        $transaction->rollBack(); //Transaction need to be rollback
                                        $errors = $merchant->getErrors();
                                        $err = [];
                                        foreach ($errors as $error) {
                                            $err[] = $error[0];
                                        }
                                        $array = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, $err, 'authentication'); //Save the result to log return the output data
                                        \Yii::$app->response->data = $array;
                                    }
                                } else { // Account is for User
                                    if (Yii::$app->ManageRequest->getVariable('environment') == "S" || Yii::$app->ManageRequest->getVariable('environment') == "P") { // Project is on staging or in Prouction
                                        Yii::$app->MailRequest->sendConfirmation($model, $lang); // Sending Account create confirmation mail to user
                                    }
                                    $usernotification = new \common\models\UserNotification(); // Saving that the user want the notification later
                                    $usernotification->user_id = $model->id;
                                    $usernotification->notification_type = 1;
                                    $usernotification->status = $offers_discount_notification;
                                    $usernotification->save(FALSE);
                                    //Generating User Authentication Session
                                    $user_auth = new \common\models\Authentication(); //Creating User session include authkey, refresh key, expiry time etc
                                    $current_time = strtotime(date('Y-m-d H:i:s'));
                                    $exp_time = strtotime('+24 hours', $current_time);
                                    $check_auth_exist = \common\models\Authentication::find()->where(['user_id' => $model->id, 'device_id' => $device_id])->andWhere("expiry_time > '" . $current_time . "'")->one(); // Chekc there is a valid authenitcation instanse exist
                                    if ($check_auth_exist == NULL) { // Not Exist Create one
                                        $user_auth->auth_key = Yii::$app->security->generateRandomString();
                                        $user_auth->refresh_token = bin2hex(random_bytes(25));
                                        $user_auth->expiry_time = $exp_time;
                                        $user_auth->user_id = $model->id;
                                        $user_auth->account_type_id = $model->user_type;
                                        $user_auth->status = 1;
                                        $user_auth->device_type = $device_type;
                                        $user_auth->device_id = $device_id;
                                        $user_auth->fb_token = $fb_token;
                                        $user_auth->save(FALSE);
                                    } else { // Exist then use old one
                                        $user_auth = $check_auth_exist;
                                    }
                                    //Setting output params
                                    $user['full_name'] = $model->first_name;
                                    $user['user_id'] = $model->id;
                                    $user['authToken'] = $user_auth->auth_key;
                                    $user['refreshToken'] = $user_auth->refresh_token;
                                    $user['email'] = $model->email;
                                    $user['account_type'] = $model->account_type;
                                    $user['expiry_time'] = (int) $user_auth->expiry_time;
                                    $user['expiry_time_format'] = date('Y-m-d H:i:s', $user_auth->expiry_time);
                                    $user['default_time_zone'] = $_SERVER['REQUEST_TIME'];
                                    $user['time_zone'] = date_default_timezone_get();
                                    $transaction->commit(); // Commit The Transaction
                                    $array = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $user, 'authentication'); // Save the result to log return the output data
                                    $get_message = Yii::$app->ManageRequest->getMessage('account_created_successfully', $lang); // changing the default success message to spesific message
                                    $array['message'] = $get_message;
                                    \Yii::$app->response->data = $array; // Output as json format
                                }
                            } else { // User not able to save
                                $transaction->rollBack(); //Transaction need to be rollback
                                $errors = $model->getErrors();
                                $err = [];
                                foreach ($errors as $error) {
                                    $err[] = $error[0];
                                }
                                $array = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, $err, 'authentication'); //Save the result to log return the output data
                                \Yii::$app->response->data = $array;
                            }
                        } else { // Not Validate
                            $transaction->rollBack(); //Transaction need to be rollback
                            $errors = $model->getErrors();
                            $err = [];
                            foreach ($errors as $error) {
                                $err[] = $error[0];
                            }
                            $array = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, $err, 'authentication'); //Save the result to log return the output data
                            \Yii::$app->response->data = $array;
                        }
                    } else { // User Is not Exist with Given Email
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(422, $name, $lang, $post, (object) [], 'authentication');
                    }
                } else { // Request Have Field Error
                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $errors, 'authentication');
                    \Yii::$app->response->data = $array;
                }
            } else { // Invlaid Access Token
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'authentication');
            }
        } else { // Access Token is not send
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'authentication');
        }
    }

    public function actionLogin() {

        header('Content-type:appalication/json'); //Header content set to json
        $name = "Login"; // Name of the api
        $headers = Yii::$app->request->headers;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Set output format structure
        if (isset($headers['lang']) && $headers['lang'] != "") { //setting App language
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $json = file_get_contents('php://input'); // Getting Post Data
        $post = json_decode($json, true); // Converting into Array
        $errors = [];
        $model = new \common\models\User();
        $model->scenario = 'login_user';
        if (isset($headers['accessToken']) && $headers['accessToken'] != "") { // Chech the Access token is set or not
            if (Yii::$app->ManageRequest->validateAccessToken($headers['accessToken'])) { // Validate Acccess Token
                if (isset($post['user_type']) && $post['user_type'] != '') { // User type is may User , Merchant , Guest
                    $user_type = $post['user_type'];
                } else {
                    $user_type = "";
                    $errors['user_type'] = $post['user_type'];
                }

                if ($user_type == 1 || $user_type == 2) { // User type User(1)or Merchant (2)
                    if (isset($post['email']) && $post['email'] != '') {
                        $email = $post['email'];
                    } else {
                        $email = "";
                        $errors['email'] = $post['email'];
                    }
//                    if (isset($post['account_type']) && $post['account_type'] != '') {  // Account Type is may EMail, GMail. Facebook
//                        $account_type = $post['account_type'];
//                    } else {
//                        $account_type = "";
//                        $errors['account_type'] = $post['account_type'];
//                    }
                    if (isset($post['password']) && $post['password'] != '') { // Set Password
                        if (base64_decode($post['password'], true)) { //Checking the given password is base 64 encoded or not
                            $password = base64_decode($post['password']);
                        } else {
                            $password = "";
                            $errors['password'] = $post['password'];
                        }
                    } else {
                        $password = "";
                        $errors['password'] = $post['password'];
                    }
                }
                if (isset($post['device_id']) && $post['device_id'] != '') { // Device ID for perticular Device
                    $device_id = $post['device_id'];
                } else {
                    $device_id = "";
                    $errors['device_id'] = $post['device_id'];
                }
                if (isset($post['device_type']) && $post['device_type'] != '') { // Device Type is may (1-Android,2-IOS)
                    $device_type = $post['device_type'];
                } else {
                    $device_type = "";
                    $errors['device_type'] = $post['device_type'];
                }
                if (isset($post['fb_token']) && $post['fb_token'] != '') { //Firebase Token is for push notification through firebase
                    $fb_token = $post['fb_token'];
                } else {
                    $fb_token = "";
                    $errors['fb_token'] = $post['fb_token'];
                }

                if ($errors == NULL) { // Any Field Error
                    if ($user_type == 1 || $user_type == 2) { // User Type is User or merchant
//                        if ($account_type == 1 || $account_type == 2 || $account_type == 3) { // If Account Typw is for Gmail
                        $modelLogin = new LoginForm(); // Create Login Instance
                        $modelLogin->email = $email;
                        $modelLogin->password = $password;
                        if ($modelLogin->login()) { // Login
                            $get_user = \common\models\User::find()->where(['email' => $email])->one(); // Get User info base on email
                            if ($get_user != NULL) { // Exist
                                if ($get_user->status == 10) { // Check user is valid
                                    $user_auth = new \common\models\Authentication(); // create User authentication instanse
                                    $current_time = strtotime(date('Y-m-d H:i:s'));
                                    $exp_time = strtotime('+24 hours', $current_time);

                                    $check_auth_exist = \common\models\Authentication::find()->where(['user_id' => $get_user->id, 'device_id' => $device_id])->andWhere("expiry_time > '" . $current_time . "'")->one(); // Check there is a valid user authentication instance
                                    if ($check_auth_exist == NULL) { // Not Exist
                                        // Generate new authentication instance
                                        $user_auth->auth_key = Yii::$app->security->generateRandomString();
                                        $user_auth->refresh_token = bin2hex(random_bytes(25));
                                        $user_auth->expiry_time = $exp_time;
                                        $user_auth->user_id = $get_user->id;
                                        $user_auth->account_type_id = 1;
                                        $user_auth->status = 1;
                                        $user_auth->device_type = $device_type;
                                        $user_auth->device_id = $device_id;
                                        $user_auth->fb_token = $fb_token;
                                        $user_auth->save(FALSE);
                                    } else { //Exist
                                        $user_auth = $check_auth_exist; // move with old one
                                    }
                                    //Setting Output Stucture
                                    $user['full_name'] = $get_user->first_name;
                                    $user['user_id'] = $get_user->id;
                                    $user['authToken'] = $user_auth->auth_key;
                                    $user['refreshToken'] = $user_auth->refresh_token;
                                    $user['email'] = $get_user->email;
                                    $user['account_type'] = $get_user->account_type;
                                    $user['expiry_time'] = (int) $user_auth->expiry_time;
                                    $user['expiry_time_format'] = date("Y-m-d\TH:i:s\Z", $user_auth->expiry_time);
                                    $user['default_time_zone'] = $_SERVER['REQUEST_TIME'];
                                    $user['time_zone'] = date_default_timezone_get();
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $user, 'authentication'); //Save the result to log return the output data
                                } else { // User is not valid one
                                    $array['user_id'] = $get_user->id;
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(426, $name, $lang, $post, $array, 'authentication');
                                }
                            } else { // User is not exist with given details
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(408, $name, $lang, $post, (object) [], 'authentication');
                            }
                        } else { // Not able to login
                            $get_user = \common\models\User::find()->where(['email' => $post['email']])->one();
                            if ($get_user != NULL && $get_user->status == 1) { // Check the user is inactive
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(426, $name, $lang, $post, (object) [], 'authentication');
                            } else {
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(408, $name, $lang, $post, (object) [], 'authentication');
                            }
                        }
//                        }
                    } else if ($user_type == 3) { // User is a guest
                        $model = new \common\models\User(); // Create User Instanse
                        $model->scenario = "guest_user";
                        $model->user_type = $user_type;
                        $model->first_name = "Guest";
                        $model->last_name = "User";
                        $model->status = 10;
                        $model->updated_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                        $model->created_by_type = 1; //1-User , 2- Admin, 3-Merchant, 4-Franchise
                        $model->app_lang_id = "1";
                        $transaction = Yii::$app->db->beginTransaction();
                        if ($model->save()) { // Create and save a user
                            $model->updated_by = $model->id;
                            $model->created_by = $model->id;
                            $model->save(FALSE); // update the user with creeated_by,updated_by filed
                            $user_auth = new \common\models\Authentication();
                            $current_time = strtotime(date('Y-m-d H:i:s'));
                            $exp_time = strtotime('+24 hours', $current_time);
                            // Checking the User instance is exist for the device ID as Guest
                            $check_auth_datas = \common\models\Authentication::find()->where(['device_id' => $device_id])->all();
                            $check_auth_exist = [];
                            if ($check_auth_datas != NULL) {
                                foreach ($check_auth_datas as $check_auth) {
                                    if ($check_auth->user->user_type == 3) {
                                        $check_auth_exist = $check_auth;
                                    }
                                }
                            }
                            if ($check_auth_exist == NULL) { // If User instance is not Exist the create one
                                $user_auth->auth_key = Yii::$app->security->generateRandomString();
                                $user_auth->refresh_token = bin2hex(random_bytes(25));
                                $user_auth->expiry_time = $exp_time;
                                $user_auth->user_id = $model->id;
                                $user_auth->account_type_id = $model->user_type;
                                $user_auth->status = 1;
                                $user_auth->device_type = $device_type;
                                $user_auth->device_id = $device_id;
                                $user_auth->fb_token = $fb_token;
                                if ($user_auth->save()) { // Save user auth Instance
                                    $transaction->commit(); // Commit DB Transaction
                                    //Creating the Output Struction
                                    $user['full_name'] = $model->first_name;
                                    $user['user_id'] = $model->id;
                                    $user['authToken'] = $user_auth->auth_key;
                                    $user['refreshToken'] = $user_auth->refresh_token;
                                    $user['email'] = "";
                                    $user['expiry_time'] = (int) $user_auth->expiry_time;
                                    $user['expiry_time_format'] = date('Y-m-d H:i:s', $user_auth->expiry_time);
                                    $user['default_time_zone'] = $_SERVER['REQUEST_TIME'];
                                    $user['time_zone'] = date_default_timezone_get();
                                    \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $user, 'authentication');
                                } else { // Can't Save user auth instance
                                    $transaction->rollBack();
                                    $errors = $user_auth->getErrors();
                                    $err = [];
                                    foreach ($errors as $error) {
                                        $err[] = $error[0];
                                    }
                                    $array = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, $err, 'authentication');
                                    \Yii::$app->response->data = $array;
                                }
                            } else { //AUth instance Exist
                                $user_auth = $check_auth_exist;
                                $transaction->rollBack(); // Bloking to create new user instance to the 'User' Table
                                if ($user_auth->expiry_time <= $current_time) {
                                    $user_auth->auth_key = Yii::$app->security->generateRandomString();
                                    $user_auth->refresh_token = bin2hex(random_bytes(25));
                                    $user_auth->expiry_time = $exp_time;
                                    $user_auth->save(); //Update the existing user with new data
                                }
                                //Creating the Output Struction

                                $user['full_name'] = $user_auth->user->first_name;
                                $user['user_id'] = $user_auth->user_id;
                                $user['authToken'] = $user_auth->auth_key;
                                $user['refreshToken'] = $user_auth->refresh_token;
                                $user['email'] = "";
                                $user['expiry_time'] = (int) $user_auth->expiry_time;
                                $user['expiry_time_format'] = date('Y-m-d H:i:s', $user_auth->expiry_time);
                                $user['default_time_zone'] = $_SERVER['REQUEST_TIME'];
                                $user['time_zone'] = date_default_timezone_get();
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $user, 'authentication');
                            }
                        } else { // Model Rules Failed
                            $transaction->rollBack();
                            $errors = $model->getErrors();
                            $err = [];
                            foreach ($errors as $error) {
                                $err[] = $error[0];
                            }
                            $array = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, $err, 'authentication');
                        }
                    }
                } else { //Input Field have erros
                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $errors, 'authentication');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'authentication');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'authentication');
        }
    }

    public function actionForgotPassword() {
        header('Content-type:appalication/json'); //Header content set to json
        $name = "Forgot Password"; // Name of the api
        $headers = Yii::$app->request->headers;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Set output format structure
        if (isset($headers['lang']) && $headers['lang'] != "") { //setting App language
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $json = file_get_contents('php://input'); // Getting Post Data
        $post = json_decode($json, true); // Converting into Array
        $errors = [];
        $array = [];
        if (isset($headers['accessToken']) && $headers['accessToken'] != "") { // Chech the Access token is set or not
            if (Yii::$app->ManageRequest->validateAccessToken($headers['accessToken'])) { // Validate Acccess Token
                if (isset($post['email']) && $post['email'] != '') { // EMail is set or not
                    $email = $post['email'];

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // EMial is Not valid emial and checkig is exist in out db or not
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(416, $name, $lang, $post, (object) [], 'authentication');
                        Yii::$app->end();
                    } else { // Valid and exist EMail
                        $check_model = \common\models\User::find()->where(['email' => $post['email']])->one(); // Find User
                        if ($check_model == NULL) { //User is Emapty
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(417, $name, $lang, $post, (object) [], 'authentication');
                            Yii::$app->end();
                        }
                    }
                } else {
                    $email = "";
                    $errors['email'] = $post['email'];
                }
                $model = new \frontend\models\PasswordResetRequestForm;
                $model->email = $post['email'];
                if ($errors == NULL) { //Field Error is Empty
                    if ($model->validate()) { // Validare model Rule
                        $check_model = \common\models\User::find()->where(['email' => $post['email'], 'status' => 10])->one(); // Check Valid User Exist With Give Email
                        if ($check_model != NULL) { //Exist
                            if (Yii::$app->ManageRequest->getVariable('environment') == "S" || Yii::$app->ManageRequest->getVariable('environment') == "P") { // Project is on staging or in Prouction
                                $mailsend = Yii::$app->MailRequest->sendLostEmail($model, $lang);
                            } else {
                                $mailsend['status'] = 200;
                                $mailsend['data'] = $check_model;
                                $mailsend['error'] = [];
                            }
                            if ($mailsend['status'] == 200) { //mail send
                                //Setting output Data
                                $user_data = $mailsend['data'];
                                $data['user_id'] = $check_model->id;
//                                $data['otp'] = $user_data->password_reset_token;
                                $get_message = Yii::$app->ManageRequest->getMessage('password_reset_message', $lang);
                                $array = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $data, 'authentication');
                                $array['message'] = $get_message;
                                \Yii::$app->response->data = $array;
                            } else {// Not send the email
                                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode($mailsend['status'], $name, $lang, $post, $mailsend['error'], 'authentication');
                            }
                        } else { // User is not exist with given email
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(417, $name, $lang, $post, (object) [], 'authentication');
                        }
                    } else { // Model Rules Validation Failed
                        $errors = $model->getErrors();
                        $err = [];
                        foreach ($errors as $error) {
                            $err[] = $error[0];
                        }
                        $array = Yii::$app->ManageRequest->getCode(411, $name, $lang, $post, $err, 'authentication');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Input Fields have error
                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $errors, 'authentication');

                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'authentication');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'authentication');
        }
    }

    public function actionVerifyPasswordResetToken() {
        $name = "Very Password Reset Token";
        header('Content-type:appalication/json'); //Header content set to json
        $headers = Yii::$app->request->headers;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Set output format structure
        if (isset($headers['lang']) && $headers['lang'] != "") { //setting App language
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $json = file_get_contents('php://input'); // Getting Post Data
        $post = json_decode($json, true); // Converting into Array
        $errors = [];

        if (isset($headers['accessToken']) && $headers['accessToken'] != "") { // Chech the Access token is set or not
            if (Yii::$app->ManageRequest->validateAccessToken($headers['accessToken'])) { // Validate Acccess Token
                if (isset($post['reset_token']) && $post['reset_token'] != '') { // Reset token that send to email
                    $reset_token = $post['reset_token'];
                } else {
                    $reset_token = "";
                    $errors[] = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post['reset_token'], (object) [], 'authentication');
                }
                if (isset($post['email']) && $post['email'] != '') {
                    $email = $post['email'];
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(416, $name, $lang, $post, (object) [], 'authentication');
                        Yii::$app->end();
                    }
                } else {
                    $email = "";
                    $errors[] = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post['email'], [], 'authentication');
                }
                if ($errors == NULL) { // No field Errors
                    $check_model = \common\models\User::find()->where(['password_reset_token' => $post['reset_token'], 'email' => $email, 'status' => 10])->one(); // Check the user is exist based on given information
                    if ($check_model != NULL) { //  Exist
                        $get_message = Yii::$app->ManageRequest->getMessage('set_new_password', $lang); // Customise the out put message
                        $array = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, (object) [], 'authentication');
                        $array['message'] = $get_message;
                        \Yii::$app->response->data = $array;
                    } else { // Not Exist
                        $array['data'] = [];
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(410, $name, $lang, $post, (object) [], 'authentication');
                    }
                } else { // Feild Errors there
                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $errors, 'authentication');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'authentication');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'authentication');
        }
    }

    public function actionSetNewPassword() {

        $name = "Set New Password";
        header('Content-type:appalication/json'); //Header content set to json
        $headers = Yii::$app->request->headers;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Set output format structure
        if (isset($headers['lang']) && $headers['lang'] != "") { //setting App language
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        $json = file_get_contents('php://input'); // Getting Post Data
        $post = json_decode($json, true); // Converting into Array
        $errors = [];
        if (isset($headers['accessToken']) && $headers['accessToken'] != "") { // Chech the Access token is set or not
            if (Yii::$app->ManageRequest->validateAccessToken($headers['accessToken'])) { // Validate Acccess Token
                if (isset($post['password']) && $post['password'] != '') {
                    $password = base64_decode($post['password']);
                } else {
                    $password = "";
                    $errors['password'] = $post['password'];
                }
                if (isset($post['email']) && $post['email'] != '') {
                    $email = $post['email'];
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Chekeck there no user exist with this email
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(416, $name, $lang, $post, (object) [], 'authentication');
                        Yii::$app->end();
                    } else { // there is a user with this email
                        $check_model = \common\models\User::find()->where(['email' => $post['email'], 'status' => 10])->one();
                        if ($check_model == NULL) {
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(417, $name, $lang, $post, (object) [], 'authentication');
                            Yii::$app->end();
                        }
                    }
                } else {
                    $email = "";
                    $errors['email'] = $post['email'];
                }
                if (isset($post['reset_token']) && $post['reset_token'] != '') {
                    $reset_token = $post['reset_token'];
                } else {
                    $reset_token = "";
                    $errors['reset_token'] = $post['reset_token'];
                }
                if (isset($post['device_id']) && $post['device_id'] != '') {
                    $device_id = $post['device_id'];
                } else {
                    $device_id = "";
                    $errors['device_id'] = $post['device_id'];
                }
                if (isset($post['device_type']) && $post['device_type'] != '') {
                    $device_type = $post['device_type'];
                } else {
                    $device_type = "";
                    $errors['device_type'] = $post['device_type'];
                }
                if (isset($post['fb_token']) && $post['fb_token'] != '') {
                    $fb_token = $post['fb_token'];
                } else {
                    $fb_token = "";
                    $errors['fb_token'] = $post['fb_token'];
                }
                if ($errors == NULL) { // Field Error are empty
                    $model = \common\models\User::find()->where(['password_reset_token' => $post['reset_token'], 'email' => $email, 'status' => 10])->one(); //chekc user exist with given details
                    if ($model != NULL) { //exist
                        $model->password = Yii::$app->security->generatePasswordHash($password); // Generate new Hashed Password
                        if ($model->save(false)) { // Model instanse saved
                            $model->status = 10;
                            $model->password_reset_token = "";
                            $model->save(false);
                            $user_auth = new \common\models\Authentication();
                            $current_time = strtotime(date('Y-m-d H:i:s'));
                            $exp_time = strtotime('+24 hours', $current_time);
                            $check_auth_exist = \common\models\Authentication::find()->where(['user_id' => $model->id, 'device_id' => $device_id])->andWhere("expiry_time > '" . $current_time . "'")->one(); // Chekc there is active instanse exist for this user
                            if ($check_auth_exist == NULL) { // Not Exist Generate New one
                                $user_auth->auth_key = Yii::$app->security->generateRandomString();
                                $user_auth->refresh_token = bin2hex(random_bytes(25));
                                $user_auth->expiry_time = $exp_time;
                                $user_auth->user_id = $model->id;
                                $user_auth->account_type_id = $model->user_type;
                                $user_auth->status = 1;
                                $user_auth->device_type = $device_type;
                                $user_auth->device_id = $device_id;
                                $user_auth->fb_token = $fb_token;
                                $user_auth->save(FALSE);
                            } else { //Exist
                                $user_auth = $check_auth_exist;
                            }
                            //Creating Output Data
                            $user['full_name'] = $model->first_name;
                            $user['user_id'] = $model->id;
                            $user['authToken'] = $user_auth->auth_key;
                            $user['refreshToken'] = $user_auth->refresh_token;
                            $user['email'] = $model->email;
                            $user['expiry_time'] = (int) $user_auth->expiry_time;
                            $user['expiry_time_format'] = date('Y-m-d H:i:s', $user_auth->expiry_time);
                            $user['default_time_zone'] = $_SERVER['REQUEST_TIME'];
                            $user['time_zone'] = date_default_timezone_get();
                            $get_message = Yii::$app->ManageRequest->getMessage('password-reset-success', $lang);
                            $array = Yii::$app->ManageRequest->getCode(200, $name, $lang, $post, $user, 'authentication');
                            $array['message'] = $get_message;
                            \Yii::$app->response->data = $array;
                        } else { //Model Not save
                            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(409, $name, $lang, $post, (object) [], 'authentication');
                        }
                    } else { // User Not Exist
                        \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(410, $name, $lang, $post, (object) [], 'authentication');
                    }
                } else { // Field Error Exist
                    $array = Yii::$app->ManageRequest->getCode(412, $name, $lang, $post, $errors, 'authentication');

                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'authentication');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'authentication');
        }
    }

    public function actionGetNewAuthToken() {
        $name = "Get New Auth token";
        $this->layout = false;
        header('Content-type:appalication/json'); //Header content set to json
        $headers = Yii::$app->request->headers;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Set output format structure
        if (isset($headers['lang']) && $headers['lang'] != "") { //setting App language
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        if (isset($headers['accessToken']) && $headers['accessToken'] != "") { // Chech the Access token is set or not
            if (Yii::$app->ManageRequest->validateAccessToken($headers['accessToken'])) { // Validate Acccess Token
                if (isset($headers['refreshToken']) && $headers['refreshToken'] != "") { // Check there is Refresh TOken Provider
                    $current_time = strtotime(date('Y-m-d H:i:s'));
                    $get_token = \common\models\Authentication::find()->where(['refresh_token' => $headers['refreshToken'], 'status' => 1])->one(); //Get User Auth instanse based ont the given details
                    if ($get_token != NULL) { // Exist
                        $exp_time = strtotime('+24 hours', $current_time);
                        $get_token->refresh_token = bin2hex(random_bytes(25));
                        $get_token->expiry_time = $exp_time;
                        $get_token->save(FALSE); // Update the Auth instance
                        //Creating Ouput Structure
                        $data['full_name'] = $get_token->user->first_name;
                        $data['user_id'] = $get_token->user_id;
                        $data['authToken'] = $get_token->auth_key;
                        $data['refreshToken'] = $get_token->refresh_token;
                        $data['email'] = $get_token->user->email != null ? $get_token->user->email : "";
                        $data['expiry_time'] = (int) $get_token->expiry_time;
                        $data['expiry_time_format'] = date('Y-m-d H:i:s', $get_token->expiry_time);
                        $data['default_time_zone'] = $_SERVER['REQUEST_TIME'];
                        $data['time_zone'] = date_default_timezone_get();
                        $array = Yii::$app->ManageRequest->getCode(200, $name, $lang, [], $data, 'authentication');
                        \Yii::$app->response->data = $array;
                    } else { // Not Exist
                        $array = Yii::$app->ManageRequest->getCode(445, $name, $lang, [], (object) [], 'authentication');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Refresh Token Not Provided
                    $array = Yii::$app->ManageRequest->getCode(444, $name, $lang, [], (object) [], 'authentication');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'authentication');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'authentication');
        }
    }

    public function actionEmailUpdation($auth = null, $email = null) { //
        if ($auth !== null && $email !== null) {
            $url = Yii::$app->request->baseUrl . '/site/activation-success';
            $user = \common\models\User::find()->where(['auth_key' => $auth])->one();
            if ($user != NULL) {

                $user->status = 10;
                $user->emailverify = 1;

                if ($user->save()) {
                    Yii::$app->session->setFlash('success', "Your Email Verified Success Fully. Now you can able to login with AGOOGO App");
                    $this->redirect([$url]);
                } else {
                    Yii::$app->session->setFlash('error', 'Sorry, Connectivity issue. please tyr later!..');
                    $this->redirect([$url]);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, Your account is noy yet registered with us!..');
                $this->redirect([$url]);
            }
        } else {

            throw new NotFoundHttpException(Yii::
            t('frontend', 'The requested page does not exist.'));
        }
    }

    public function actionLogOut() {
        $name = "Log out";
        header('Content-type:appalication/json'); //Header content set to json
        $headers = Yii::$app->request->headers;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Set output format structure
        if (isset($headers['lang']) && $headers['lang'] != "") { //setting App language
            $lang = $headers['lang'];
        } else {
            $lang = "1";
        }
        $lang = intval(trim($lang, '"')); // Language into Integer
        if (isset($headers['accessToken']) && $headers['accessToken'] != "") { // Chech the Access token is set or not
            if (Yii::$app->ManageRequest->validateAccessToken($headers['accessToken'])) { // Validate Acccess Token
                if (isset($headers['authToken']) && $headers['authToken'] != "") { //Check the AuthToken Is Set
                    if (Yii::$app->ManageRequest->validateAuthToken($headers['authToken']) != NULL) { // Check The authToken is Valids
                        $value = Yii::$app->ManageRequest->validateAuthToken($headers['authToken']);
                        $user_details = \common\models\User::find()->where(['id' => $value, 'status' => 10])->one(); // get use by using authtoken

                        if ($user_details != NULL) { //exist
                            $authentication = \common\models\Authentication::find()->where(['user_id' => $user_details->id, 'status' => 1, 'auth_key' => $headers ['authToken']])->one(); // Geting instanse of the user
                            if ($authentication != NULL) { // auth instanse exist
                                $authentication->delete(); // Delete the exist auth token
                            }
                            $array = Yii::$app->ManageRequest->getCode(200, $name, $lang, [], (object) [], 'authentication');
                            unset($array['data']);
                            $array['data']['value']['user_id'] = $user_details->id;
                            \Yii::$app->response->data = $array;
                        } else { // Not Exist
                            $array = Yii::$app->ManageRequest->getCode(420, $name, $lang, [], (object) [], 'authentication');
                            $array['data'] = [];
                            \Yii::$app->response->statusCode = 401;
                            \Yii::$app->response->statusText = $array['message'];
                            \Yii::$app->response->data = $array;
                        }
                    } else { //Un autherised Auth Token
                        $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'authentication');
                        \Yii::$app->response->data = $array;
                    }
                } else { // Auth token not provided
                    $array = Yii::$app->ManageRequest->getCode(401, $name, $lang, [], (object) [], 'authentication');
                    \Yii::$app->response->data = $array;
                }
            } else { // Access TOken is Valid
                \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(406, $name, $lang, [], (object) [], 'authentication');
            }
        } else { // Access TOken is empty or not provided
            \Yii::$app->response->data = Yii::$app->ManageRequest->getCode(407, $name, $lang, [], (object) [], 'authentication');
        }
    }

}
