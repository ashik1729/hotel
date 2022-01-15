<?php

namespace common\components;

use Yii;
use yii\base\Component;
use common\models\Notification;
use backend\helpers\FirebaseNotifications;
use common\components\NotificationManager;

class LogManagement extends \yii\base\Component {

    public function create($code, $name, $input = [], $output = [], $folder) {

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
            } else {
                $otp = "Output: " . $output;
            }
        }
        fwrite($fp, "\r\n" . $write_data);
        fwrite($fp, "\r\n" . $imp);
        fwrite($fp, "\r\n" . $otp);
        fclose($fp);
    }

}
