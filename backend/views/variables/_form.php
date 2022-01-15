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
                <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">


                <?= $form->field($model, 'key_value')->textInput(['maxlength' => true]) ?>





            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">

                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>

            </div>
        </div>


    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
