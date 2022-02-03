<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class ManageRequest extends Component {

    function getCode($code, $name, $lang, $input = [], $output = [], $folder = "misc") {
        $get_code = \common\models\ErrorCode::find()->where(['error_code' => $code])->one();
        $retun = [];

        $targetFolder = \yii::$app->basePath . '/../uploads/logs/' . $folder . '/';
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }
        $targetfile = $targetFolder . '/log.txt';
        if (!file_exists($targetfile)) {
            touch($targetfile);
            chmod($targetfile, 0644);
        }
        $file_size = filesize($targetfile);
        $size = $file_size / 1000;
        if ($size >= 1000) {
            $old_name = $targetfile;
            $new_name = $targetFolder . "/log_" . date('Y-m-d_H-i-s') . md5('Y-m-d H:i:s') . ".txt";
            rename($old_name, $new_name);
            $fp = fopen($targetfile, "a") or die("Unable to open file!");
        } else {
            $fp = fopen($targetfile, "a") or die("Unable to open file!");
        }

        if ($code != 200) {
            $write_data = date('Y-m-d H:i:s A') . ' - ' . $name . ' - Error code : ' . $code;
        } else {

            $write_data = date('Y-m-d H:i:s A') . ' - ' . $name . ' - Success : ' . $code;
        }
        $imp = '';
        $otp = '';

        if ($input != NULL) {
            if (is_array($input)) {
                $imp = "Input: " . json_encode($input);
            } else {
                $imp = "Input: " . $input;
            }
        }
        if ($output != NULL) {

            if (is_array($output)) {
                $otp = "Output: " . json_encode($output);
            } else if (is_object($output)) {

                $otp = "Output: " . json_encode((array) $output);
            } else {
                $otp = "Output: " . $output;
            }
        }
        fwrite($fp, "\r\n" . $write_data);
        fwrite($fp, "\r\n" . $imp);
        fwrite($fp, "\r\n" . $otp);
        fclose($fp);


        if ($get_code != NULL) {

            $retun['status'] = $get_code->error_code;
            $lang = intval(trim($lang, '"'));
            if ($lang == 2) {
                $retun['message'] = $get_code->error_ar;
            } else {
                $retun['message'] = $get_code->error_en;
            }
        }
        $feilds = [];
        if ($code == 412) {
            if ($output != NULL) {
                foreach ($output as $key => $val) {
                    array_push($feilds, ['name' => $key, 'field_value' => $val]);
                }
            }
        }
        $result = $retun;
        if ($code == 412) {

            $result['data']['value']['fields'] = $feilds;
        } else {

            if (empty($output)) {
                $result['data'] = (object) [];
            } else if (empty((array) ($output))) {
                $result['data'] = (object) [];
            } else {
                $result['data']['value'] = $output;
            }
        }
        return $result;
    }

    function getImage($model) {
        if (isset($model->id) && $model->id > 0 && isset($model->image) && $model->image !== "") {

            $imgPath = ((yii\helpers\Url::base())) . '/../uploads/products/' . base64_encode($model->id) . '/image/' . $model->image;
            $imgBasePath = \Yii::$app->basePath . '/../uploads/products/' . base64_encode($model->id) . '/image/' . $model->image;

            if (!file_exists($imgBasePath)) {
                $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
            }
        } else {
            $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
        }
        return $imgPath;
    }

    public function addHistory($rid, $status, $comment = "", $created_by, $created_by_type = 0) {

        $model = new \common\models\ReservationHistory();
        $model->reservation_id = $rid;
        $model->status = $status;
        $model->comment = $comment;
        $model->created_by = $created_by;
        $model->created_by_type = $created_by_type;

        if ($model->save(FALSE)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function resolvePathInfo() {
        if ($this->getUrl() === $this->adminUrl) {
            return "";
        } else {
            return parent::resolvePathInfo();
        }
    }

    public function getVariable($variable) {
        $get_variables = \common\models\Variables::find()->where(['key_name' => $variable])->one();
        if ($get_variables != NULL) {
            return $get_variables->key_value;
        } else {
            return false;
        }
    }

    function getMessage($message, $lang) {
        $get_message = \common\models\MobileStrings::find()->where(['string_key' =>
                    $message])->one();

        if ($get_message != NULL) {

            $lang = intval(trim($lang, '"'));
            if ($lang == 2) {
                return $get_message->string_ar;
            } else {
                return $get_message->string_en;
            }
        } else {
            return "";
        }
    }

    function sendNotif($template_key = [], $desc_key, $title_key, $reciever_list, $notif_key = [], $notification_type, $reciever_type) {

        $tokens = [];
        $tokens_ios = [];
        $tokens_ar = [];
        $tokens_ar_ios = [];


        $reciever_list = array_filter($reciever_list);
        $available_reciever = [];
        if ($reciever_list != NULL) {
            foreach ($reciever_list as $us) {
                $get_device = \common\models\Authentication::find()->where(['user_id' => $us, 'status' => 1])->all();
                if ($get_device != NULL) {
                    foreach ($get_device as $get_d) {
                        $check_desable = \common\models\UserNotification::find()->where(['user_id' => $get_d->user_id, 'notification_type' => $notification_type, 'status' => 0])->one();
                        if ($check_desable == NULL) {
                            $available_reciever[] = $get_d->user_id;
                            $get_us = \common\models\Users::find()->where(['id' => $get_d->user_id])->one();
                            if ($get_us->app_lang_id == 1) {
                                if ($get_d->device_type == 0) {
                                    $tokens[] = $get_d->fb_token;
                                } else {
                                    $tokens_ios[] = $get_d->fb_token;
                                }
                            } else {
                                if ($get_d->device_type == 0) {
                                    $tokens_ar[] = $get_d->fb_token;
                                } else {
                                    $tokens_ar_ios[] = $get_d->fb_token;
                                }
                            }
                        }
                    }
                }
            }
        }

        $tokens = $this->getFilterToken($tokens);
        $tokens_ar = $this->getFilterToken($tokens_ar);
        $tokens_ar_ios = $this->getFilterToken($tokens_ar_ios);
        $tokens_ios = $this->getFilterToken($tokens_ios);

        $title_en = $this->getMessage($title_key, 1);
        $title_ar = $this->getMessage($title_key, 2);

        $body_en = $this->getBody($desc_key, $template_key, 1);
        $body_ar = $this->getBody($desc_key, $template_key, 2);



        $newtk = [];
        if ($tokens != NULL) {
            foreach ($tokens as $tok) {
                $newtk [] = $tok;
            }
            Yii::$app->notificationManager->sendnotification($newtk, $title_en, $body_en, $notif_key, $app = 0);
        }


        $newtk_ios = [];
        if ($tokens_ios != NULL) {
            foreach ($tokens_ios as $tokios) {
                $newtk_ios [] = $tokios;
            }
            Yii::$app->notificationManager->sendnotification($newtk_ios, $title_en, $body_en, $notif_key, $app = 1);
        }




        $newtk_ar = [];
        if ($tokens_ar != NULL) {
            foreach ($tokens_ar as $tokr) {
                $newtk_ar [] = $tokr;
            }
            Yii::$app->notificationManager->sendnotification($newtk_ar, $title_ar, $body_ar, $notif_key, $app = 0);
        }

        $newtk_ar_ios = [];
        if ($tokens_ar_ios != NULL) {
            foreach ($tokens_ar_ios as $tokrios) {
                $newtk_ar_ios [] = $tokrios;
            }
            Yii::$app->notificationManager->sendnotification($newtk_ar_ios, $title_ar, $body_ar, $notif_key, $app = 1);
        }

        if ($available_reciever != NULL) {
            $available_reciever = array_unique($available_reciever);
            foreach ($available_reciever as $reciever) {
                $courier_notifications = Yii:: $app->notificationManager->notifications($notification_type, $reciever, $title_en, $title_ar, $reciever_type, $body_en, $body_ar, $notif_key);
            }
        }
    }

    function sendNotifChat($template_key = [], $desc_key, $title_key, $reciever_list, $notif_key = [], $notification_type, $reciever_type) {

        $tokens = [];
        $tokens_ios = [];
        $tokens_ar = [];
        $tokens_ar_ios = [];


        $reciever_list = array_filter($reciever_list);
        $available_reciever = [];
        if ($reciever_list != NULL) {
            foreach ($reciever_list as $us) {
                $get_device = \common\models\Authentication::find()->where(['user_id' => $us, 'status' => 1])->all();
                if ($get_device != NULL) {
                    foreach ($get_device as $get_d) {
                        $check_desable = \common\models\UserNotification::find()->where(['user_id' => $get_d->user_id, 'notification_type' => $notification_type, 'status' => 0])->one();
                        if ($check_desable == NULL) {
                            $available_reciever[] = $get_d->user_id;
                            $get_us = \common\models\Users::find()->where(['id' => $get_d->user_id])->one();
                            if ($get_us->app_lang_id == 1) {
                                if ($get_d->device_type == 0) {
                                    $tokens[] = $get_d->fb_token;
                                } else {
                                    $tokens_ios[] = $get_d->fb_token;
                                }
                            } else {
                                if ($get_d->device_type == 0) {
                                    $tokens_ar[] = $get_d->fb_token;
                                } else {
                                    $tokens_ar_ios[] = $get_d->fb_token;
                                }
                            }
                        }
                    }
                }
            }
        }

        $tokens = $this->getFilterToken($tokens);
        $tokens_ar = $this->getFilterToken($tokens_ar);
        $tokens_ar_ios = $this->getFilterToken($tokens_ar_ios);
        $tokens_ios = $this->getFilterToken($tokens_ios);

        $title_en = $title_key;
        $title_ar = $title_key;

        $body_en = $this->getBody($desc_key, $template_key, 1);
        $body_ar = $this->getBody($desc_key, $template_key, 2);



        $newtk = [];
        if ($tokens != NULL) {
            foreach ($tokens as $tok) {
                $newtk [] = $tok;
            }
            Yii::$app->notificationManager->sendnotification($newtk, $title_en, $body_en, $notif_key, $app = 0);
        }


        $newtk_ios = [];
        if ($tokens_ios != NULL) {
            foreach ($tokens_ios as $tokios) {
                $newtk_ios [] = $tokios;
            }
            Yii::$app->notificationManager->sendnotification($newtk_ios, $title_en, $body_en, $notif_key, $app = 1);
        }




        $newtk_ar = [];
        if ($tokens_ar != NULL) {
            foreach ($tokens_ar as $tokr) {
                $newtk_ar [] = $tokr;
            }
            Yii::$app->notificationManager->sendnotification($newtk_ar, $title_ar, $body_ar, $notif_key, $app = 0);
        }

        $newtk_ar_ios = [];
        if ($tokens_ar_ios != NULL) {
            foreach ($tokens_ar_ios as $tokrios) {
                $newtk_ar_ios [] = $tokrios;
            }
            Yii::$app->notificationManager->sendnotification($newtk_ar_ios, $title_ar, $body_ar, $notif_key, $app = 1);
        }

        if ($available_reciever != NULL) {
            $available_reciever = array_unique($available_reciever);
            foreach ($available_reciever as $reciever) {
                $courier_notifications = Yii::$app->notificationManager->notifications($notification_type, $reciever, $title_en, $title_ar, $reciever_type, $body_en, $body_ar, $notif_key);
            }
        }
    }

    function getFilterToken($tokens = []) {
        $tokens = array_filter($tokens);
        $tokens = array_unique($tokens);

        $tokens = array_values($tokens);
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

    function addToCalendar($calender_date, $event_id, $event_type, $user_id, $event_type_key, $title, $desc = "", $title_ar, $desc_ar = "", $from_time, $to_time, $event_status, $device_type = 0, $event_ref_id) {
        $model = \common\models\AbrajCalendar::find()->where(['user_id' => $user_id, 'event_ref_id' => $event_ref_id, 'event_type' => $event_type])->one();

        if ($model != NULL) {
            $model->event_date = $calender_date;
            $model->title = $title;
            $model->description = $desc;
            $model->title_ar = $title_ar;
            $model->from_time = $from_time;
            $model->status = $event_status;
            $model->to_time = $to_time;
            $model->event_ref_id = $event_ref_id;
            $model->description_ar = $desc_ar;
            $model->save(FALSE);
        } else {
            $model = new \common\models\AbrajCalendar();
            $model->event_date = $calender_date;
            $model->event_id = $event_id;
            $model->event_type = $event_type;
            $model->event_ref_id = $event_ref_id;
            $model->from_time = $from_time;
            $model->to_time = $to_time;
            $model->device_type = $device_type;

            $model->status = 1;
            $model->user_id = $user_id;
            $model->event_type_key = $event_type_key;
            $model->title = $title;
            $model->description = $desc;
            $model->title_ar = $title_ar;
            $model->description_ar = $desc_ar;
            $model->save(FALSE);
        }
    }

    public function siteURL() {
        $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $domainName = $_SERVER['HTTP_HOST'];
        return $protocol . $domainName;
    }

    function getUserIP() {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

    public function validateAuthToken($token) {  // token validate, return type bool
        error_reporting(E_ALL);
        $model = \common\models\Authentication::find()->where(['auth_key' => $token, 'status' => 1])->one();
        if ($model != NULL) {

            if ($model->user->status == 10) {
                $current_time = strtotime(date('Y-m-d H:i:s'));
                $expiry_time = $model->expiry_time;
                if ($current_time < $expiry_time) {
                    return $model->user_id;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function validateAccessToken($token) {  // token validate, return type bool
        error_reporting(E_ALL);
        $model = \common\models\Franchise::find()->where(['access_token' => $token, 'status' => 10])->one();
        if ($model != NULL) {
            return true;
        } else {
            return false;
        }
    }

    function getTimeSlots($StartTime, $EndTime, $Duration = "60", $disable_slots) {
        $ReturnArray = array(); // Define output
        $StartTime = strtotime($StartTime); //Get Timestamp
        $EndTime = strtotime($EndTime); //Get Timestamp
        $AddMins = $Duration * 60;
        while ($StartTime <= $EndTime) { //Run loop
            $ReturnArray[] = date("h:i A", $StartTime);
            $StartTime += $AddMins; //Endtime check
        }

        return $ReturnArray;
    }

}
