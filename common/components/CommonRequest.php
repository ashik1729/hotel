<?php

namespace common\components;

class CommonRequest extends \yii\web\Request {

    public $web;
    public $adminUrl;

    public function getBaseUrl() {
        return str_replace($this->web, "", parent::getBaseUrl()) . $this->adminUrl;
    }

    /*
      If you don't have this function, the admin site will 404 if you leave off
      the trailing slash.

      E.g.:

      Wouldn't work:
      site.com/admin

      Would work:
      site.com/admin/

      Using this function, both will work.
     */

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

    function getMessage($message, $lang) {
        $get_message = \common\models\MobileStrings::find()->where(['string_key' =>
                    $message])->one();

        if ($get_message != NULL) {

            if ($lang == 'ar') {

                return $get_message->string_ar;
            } else {
                return $get_message->string_en;
            }
        } else {
            return "";
        }
    }

}
