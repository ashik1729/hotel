<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProductReview */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body product-review-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'author')->textInput(['disabled' => true]) ?>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'rating')->textInput() ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'approvel')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>
            </div>
        </div>
    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>