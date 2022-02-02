<?php

namespace common\components;

use Exception;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;

class MailRequest extends Component {

    public function sendConfirmation($model, $lang) {


        $to = $model->email;
        $subject = 'Email Veryfi for HCCA';



        \Yii::$app->mailer->compose('new_account', ['model' => $model, 'lang' => $lang])
                ->setFrom(['noreply@wakra-lab.com' => 'AGOGO - Account Activation'])
                ->setTo($to)
                ->setSubject($subject)
                ->send();
    }

    public function sendUserOrderConfirmation($order, $lang) {
        $to = isset($order->billAddress->email) ? $order->billAddress->email : $order->user->email;
        $subject = 'Agogo.com: Order # AGOR' . $order->id . ' :: ';
        \Yii::$app->mailer->compose('user_order_success_mail', ['order' => $order, 'lang' => $lang])
                ->setFrom(["noreply@wakra-lab.com" => 'AGOGO - Order'])
                ->setTo($to)
                ->setSubject($subject)
                ->send();
    }

    function getMessage($message, $lang) {
        $get_message = \common\models\MobileStrings::find()->where(['string_key' =>
                    $message])->one();

        if ($get_message != NULL) {

            if ($lang == 2) {

                return $get_message->string_ar;
            } else {
                return $get_message->string_en;
            }
        } else {
            return "";
        }
    }

    public function sendRegistrationMail($model) {
        $url = $this->siteURL();
        \Yii::$app->mailer->compose('register', [
                    'model' => $model, 'url' => $url])
                ->setFrom(["noreply@wakra-lab.com" => 'Agogo - Registration'])->setTo($model->email)
                ->setSubject('Welcome to Agogo')->send();
    }

    public function sendMail($to, $subject, $from, $view, $params) {
        \Yii::$app->mailer->compose($view, $params)
                ->setFrom($from)
                ->setTo($to)
                ->setSubject($subject)
                ->send();
    }

    public function actionEmailUpdation($auth = null, $email = null) {
        if ($auth !== null && $email !== null) {
            $url = Yii::$app->request->baseUrl . '/site/activation-success';
            $user = \common\models\User::find()->where(['auth_key' => $auth])->one();
            if ($user != NULL) {

                $user->status = 10;
                $user->emailverify = 1;

                if ($user->save()) {
                    Yii::$app->session->setFlash('success', "Your Email Verified Success Fully. Now you can able to login with AGOGO App");
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

    public function sendLostEmail($model, $lang) {
        $user = \common\models\User::find()->where(['status' => 10, 'email' => $model->email])->one();
        $return = [];
        if (!$user) {
            $return['status'] = 417;
            $return['data'] = [];
            $return['error'] = [];
            return $return;
        }
        // $user->password_reset_token = mt_rand(100000, 999999);
        // $user->save(false);
        $to = $user->email;
        $subject = 'Password reset for HCCA';
        try {
            \Yii::$app->mailer->compose('password_reset', ['model' => $user, 'lang' => $lang])
                    ->setFrom(["noreply@wakra-lab.com" => 'HCCA - Password Reset'])
                    ->setTo($to)
                    ->setSubject($subject)
                    ->send();
            $return['status'] = 200;
            $return['data'] = $user;
            $return['error'] = [];
        } catch (Exception $ex) {
            $return['status'] = 446;
            $return['data'] = [];
            $return['error'] = $ex;
        }

        return $return;
    }

}
