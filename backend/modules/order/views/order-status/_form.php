<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderStatus */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body order-status-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">




        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'name_ar')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'description_ar')->textarea(['rows' => 6]) ?>

            </div>
        </div>



        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable'], ['prompt' => 'Select Status']) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'sort_order')->textInput() ?>

            </div>
        </div>
    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
