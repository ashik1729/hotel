<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="contact-section">
    <div class="container">
        <div class="row">

            <div class="col-12 ">
                <?php if (Yii::$app->session->hasFlash("success")) : ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <span> <?= Yii::$app->session->getFlash("success") ?></span>
                    </div>
                <?php endif; ?>
                <?php if (Yii::$app->session->hasFlash("error")) : ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <span> <?= Yii::$app->session->getFlash("error") ?></span>
                    </div>
                <?php endif; ?>
            </div>
              
        </div>
    </div>
</section>