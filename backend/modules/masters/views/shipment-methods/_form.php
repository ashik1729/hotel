<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ShipmentMethods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body shipment-methods-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">


        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'name_ar')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">

                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable'], ['prompt' => 'Choose Status']) ?>

            </div>
        </div>


    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
