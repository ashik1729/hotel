<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\FlightRequestSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="flight-request-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'from_place') ?>

    <?= $form->field($model, 'checkin_date') ?>

    <?= $form->field($model, 'return_date') ?>

    <?= $form->field($model, 'no_adult') ?>

    <?php // echo $form->field($model, 'no_children') ?>

    <?php // echo $form->field($model, 'no_room') ?>

    <?php // echo $form->field($model, 'class') ?>

    <?php // echo $form->field($model, 'purpose') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'to_place') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>