<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Country */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body country-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'country_name')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'iso')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable'], ['prompt' => '', 'class' => 'form-control']); ?>

                <span class="bmd-help"><?= Html::activeHint($model, 'status'); ?></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'iso3')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'numcode')->textInput() ?>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'phonecode')->textInput() ?>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'country_name_ar')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
    </div>
    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
