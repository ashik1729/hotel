<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VisaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visa-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'subtitle') ?>

    <?= $form->field($model, 'short_description') ?>

    <?= $form->field($model, 'long_description') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'gallery') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'sort_order') ?>

    <?php // echo $form->field($model, 'can_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
