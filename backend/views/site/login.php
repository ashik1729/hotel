<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
?>
<style>
    .site-login{
        margin: 100px   auto;
        padding: 25px 0 0;
        position: relative;
        text-align: center;
        /* text-shadow: 0 1px 0 #fff; */
        width: 380px;
        color: #fff !important;
        text-transform: uppercase;
    }
</style>
<div class="container">

    <div class="site-login">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Admin Login</h4>
                <p class="card-category">Type your  User Name and Password</p>
            </div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group bmd-form-group">
                            <label class="bmd-label-floating">Username</label>
                            <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label(FALSE) ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group bmd-form-group">
                            <label class="bmd-label-floating">Password</label>
                            <?= $form->field($model, 'password')->passwordInput()->label(FALSE) ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'rememberMe')->checkbox() ?>
                        </div>
                    </div>
                </div>
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary pull-right', 'name' => 'login-button']) ?>
                <div class="clearfix"></div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>