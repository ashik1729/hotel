<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VisaRequestsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visa-requests-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'visa_option') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'processing_type') ?>

    <?= $form->field($model, 'no_visa') ?>

    <?php // echo $form->field($model, 'travel_date_from') ?>

    <?php // echo $form->field($model, 'travel_date_to') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
