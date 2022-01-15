<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body user-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">


        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <!--                <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'gender')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'dob')->textInput() ?>

                            </div>
                        </div>
        -->
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
        <!--
                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'profile_image')->textInput(['maxlength' => true]) ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'mobile_number')->textInput(['maxlength' => true]) ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'country')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'state')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'city')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'created_at')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'updated_at')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'status')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'newsletter')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'user_otp')->textInput(['maxlength' => true]) ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'emailverify')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'created_by')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'created_by_type')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'updated_by')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
        <?= $form->field($model, 'updated_by_type')->textInput() ?>

                            </div>
                        </div>-->
    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
