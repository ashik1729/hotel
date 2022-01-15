<?php

namespace common\components;

use Yii;
use yii\base\Component;
use common\models\Notification;
use backend\helpers\FirebaseNotifications;
use common\components\NotificationManager;

class NotificationManager extends \yii\base\Component {

    public function sendnotification($tokens, $title, $body, $key = [], $app) {
        $service = new FirebaseNotifications([
            'authKey' => Yii::$app->ManageRequest->getVariable('firebase_token')]);

        $message = array('title' => $title, 'body' => $body, 'content_available' => true, 'priority' => 'high');


        if ($key != NULL) {
            $option['data'] = $key;
            $data = $key;
        }


        $option['data']['title'] = $title;
        $option['data']['body'] = $body;
        $option['data']['priority'] = 'high';
        $option['data']['content_available'] = TRUE;

        if ($app == 2) {
            $option['notification'] = $message + $key;
            $option['data']['data'] = $data + $message;
        } else {

            $option['data']['data'] = $data + $message;
            $option['notification'] = $message + $key;
            $message = [];
        }

        return $service->sendNotification($tokens, $message, $option);
    }

    public function pushnotification($UsedID, $titleEn, $titleAr, $bodyEn, $bodyAr, $notif_key) {
        $type = $notif_key['type'];
        $name = "Firebase Notifications";

        $get_device = \common\models\Authentication::find()->where(['status' => 1, 'user_id' => $UsedID])->all();
        $tokens = [];
        if ($get_device != NULL) {
            foreach ($get_device as $get_d) {
                if ($get_d->user->status == 10) {
                    $lang = $get_d->user->app_lang_id;
                    $check_user_notification = \common\models\UserNotification::find()->where(['user_id' => $get_d->user_id, 'notification_type' => $type, 'status' => 1])->one();
                    if ($check_user_notification != NULL) {
                        if ($lang == 1) {
                            if ($get_d->device_type == 1) {
                                $tokens["enand"][] = $get_d->fb_token;
                            } else {
                                $tokens["enios"][] = $get_d->fb_token;
                            }
                        } else {
                            if ($get_d->device_type == 1) {
                                $tokens["arand"][] = $get_d->fb_token;
                            } else {
                                $tokens["arios"][] = $get_d->fb_token;
                            }
                        }
                    }
                }
            }
            // echo "<pre/>";
            if ($tokens != NULL) {
                $actions = array("enand", "enios", "arand", "arios");
                $device = ["enand" => 1, "enios" => 2, "arand" => 1, "arios" => 2];
                $language = ["enand" => 1, "enios" => 1, "arand" => 2, "arios" => 2];
                foreach ($tokens as $key => $value) {
                    if (in_array($key, $actions)) {
                        $app = $device[$key];
                        $langs = $language[$key];
                        $newtk = $this->getFilterToken($value);
                        if ($langs == 1) {
                            $title = $titleEn;
                            $body = $bodyEn;
                        } else {
                            $title = $titleAr;
                            $body = $bodyAr;
                        }
                        $input['token'] = $newtk;
                        $input['title'] = $title;
                        $input['body'] = $body;
                        $input['additional_data'] = $notif_key;
                        $input['device_type'] = $app;
                        //  print_r($input);

                        if ($newtk != NULL) {
                            $output = Yii::$app->NotificationManager->sendnotification($newtk, $title, $body, $notif_key, $app);
                            \Yii::$app->LogManagement->create(444, $name, $input, $output, 'notification');
                        }
                    }
                }
            }
            //  exit;
        }
    }

    public function sendnotificationservice($tokens, $title, $body, $key = [], $app) {
        $service = new FirebaseNotifications([
            'authKey' =>
            'AAAA0JeTves:APA91bG4YQ5Nzonadpuc7MJlc6CF7Ug5JIDiHHwiFovUqLggB5PEJYf2qOT1Qz20Rg2X3QdCpJlJ9bAErvbo6Q-w1mjsfo8oc1sKtq41PrmqhSYf1f9Ze7tjvjr6G6tNNA3u8iqeW3Bc']);

        $message = array('title' => $title, 'body' => $body);
//        $tk[] = 'c_Xnr8prGRc:APA91bE2PrrB4xYqihfiOQbX8jYkPyfCRE_cQLEly_ism3xzwdjqmSVsJYlOsOEpmIP_HoZzcaU6GQ-p0Ms-50HJdjl50hAw5hvRgks7Mef-o9mzd97PbnL_ecSVOMyliiWBXGzNqZYo';
//        echo '<pre/>';
//
//        print_r($tokens);
//
//        echo '---------';
//


        if ($key != NULL) {
            $option['data'] = $key;
        }

        $option['data']['title'] = $title;
        $option['data']['body'] = $body;
//        $option['priority'] = 'high';
        $option['content_available'] = TRUE;

        if ($app == 1) {
            $option['notification'] = $message;
        } else {
            $message = [];
        }
        $service->sendNotification($tokens, $message, $option);
    }

//    DATA='{"notification": {"body": "this is a body","title": "this is a title"}, "priority": "high", "data": {"key1": "any message", "id": "1", "status": "done"}, "to": "<FCM TOKEN>"}'
//curl https://fcm.googleapis.com/fcm/send -H "Content-Type:application/json" -X POST -d "$DATA" -H "Authorization: key=<FCM SERVER KEY>"

    public function notifications($type, $reciever, $title, $title_ar, $reciever_type, $desc, $desc_ar, $notif_key) {


        $notifications = new Notification();
        $notifications->type_id = $type;
        $notifications->title = $title;
        $notifications->title_ar = $title_ar;
        $notifications->receiver_id = $reciever;
        $notifications->description = $desc;
        $notifications->description_ar = $desc_ar;
        $notifications->status = 1;
        $notifications->reciever_type = $reciever_type;

        if ($notif_key != NULL) {
            if (array_key_exists("redirection", $notif_key)) {
                $notifications->redirection = $notif_key['redirection'];
            } else {
                $notifications->redirection = 'NOTIFICATION_LIST';
            }
        } else {
            $notifications->redirection = 'NOTIFICATION_LIST';
        }
        if ($notif_key != '') {
            $notifications->params = serialize($notif_key);
        } else {
            $notifications->params = '';
        }
        if ($notifications->save(FALSE)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function getFilterToken($tokens = []) {
        $tokens = array_filter($tokens);
        $tokens = array_unique($tokens);
        $tokens = array_values($tokens);
        return $tokens;
    }

//    public function savenotifications($type, $reciever, $title, $title_ar, $reciever_type, $desc, $desc_ar, $notif_key, $image) {
    public function savenotifications($data) {
        $error = [];
        if ($data['reciever'] != NULL) {
            foreach ($data['reciever'] as $user_id) {
                $notifications = new Notification();
                $notifications->id = uniqid('CAPON');
                $notifications->type_id = $data['notification_type'];
                $notifications->redirection_id = $data['redirection_id'];
                $notifications->title = $data['title']['en'];
                $notifications->title_ar = $data['title']['ar'];
                $notifications->receiver_id = $user_id;
                $notifications->description = $data['description']['en'];
                $notifications->description_ar = $data['description']['ar'];
                $notifications->status = 1;
                $notifications->read_status = 0;
                $notifications->reciever_type = $data['reciever_type'];
                $notifications->image = $data['marketing_image'];

                if ($data['notif_key'] != NULL) {
                    if (array_key_exists("redirection", $data['notif_key'])) {
                        $notifications->redirection = $data['notif_key']['redirection'];
                    } else {
                        $notifications->redirection = '';
                    }
                } else {
                    $notifications->redirection = '';
                }
                if ($data['notif_key'] != '') {
                    $notifications->params = serialize($data['notif_key']);
                } else {
                    $notifications->params = '';
                }
                if ($notifications->save(FALSE)) {

                } else {
                    $error[] = $notifications->errors;
                }
            }
        } else {
            $notifications = new Notification();
            $notifications->id = uniqid('CAPON');
            $notifications->type_id = $data['notification_type'];
            $notifications->title = $data['title']['en'];
            $notifications->title_ar = $data['title']['ar'];
            $notifications->receiver_id = "-1";
            $notifications->description = $data['description']['en'];
            $notifications->description_ar = $data['description']['ar'];
            $notifications->status = 1;
            $notifications->read_status = 1;
            $notifications->reciever_type = $data['reciever_type'];
            $notifications->image = $data['marketing_image'];

            if ($data['notif_key'] != NULL) {
                if (array_key_exists("redirection", $data['notif_key'])) {
                    $notifications->redirection = $data['notif_key']['redirection'];
                } else {
                    $notifications->redirection = '';
                }
            } else {
                $notifications->redirection = '';
            }
            if ($data['notif_key'] != '') {
                $notifications->params = serialize($data['notif_key']);
            } else {
                $notifications->params = '';
            }
            if ($notifications->save(FALSE)) {

            } else {
                $error[] = $notifications->errors;
            }
        }
        return $error;
    }

}
