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
                <div class="col-12 text-center">
                    <h1><?= Html::encode($this->title) ?></h1>

                    <p>Please choose your new password:</p>

                </div>
                <div class="col-12">
                    <div class="d-flex flex-lg-row justify-content-center flex-column">

                        <div class="right">
                            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false]]); ?>
                            <div class="form-group">
                                <?= $form->field($model, 'password')->textInput(['autofocus' => true, 'maxlength' => true, 'placeholder' => 'Full Name', 'class' => 'form-control'])->label(false) ?>

                            </div>

                            <div class="form-group">
                                <?= Html::submitButton('Send Message', ['class' => 'my-btn']) ?>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</section>