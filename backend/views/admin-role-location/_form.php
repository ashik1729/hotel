<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AdminRoleLocation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body admin-role-location-form">

    <?php $form = ActiveForm::begin(); ?>

    
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'role_id')->textInput() ?>

                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'status')->textInput() ?>

                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'created_at')->textInput() ?>

                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'updated_at')->textInput() ?>

                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'controller')->textInput(['maxlength' => true]) ?>

                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'role_list_id')->textInput() ?>

                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'created_by')->textInput() ?>

                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'updated_by')->textInput() ?>

                    </div>
                </div>
            </div>
                <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
