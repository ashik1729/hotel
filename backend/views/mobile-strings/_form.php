<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MobileStrings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body mobile-strings-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">


        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'module')->textInput(['maxlength' => true, 'readonly' => true, 'value' => 'HOME']) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'string_en')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'string_ar')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>

            </div>
        </div>


        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'string_key')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
