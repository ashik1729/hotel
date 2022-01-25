<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CarsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cars-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'brand') ?>

    <?= $form->field($model, 'type_of_car') ?>

    <?= $form->field($model, 'long_description') ?>

    <?php // echo $form->field($model, 'short_description') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'gallery') ?>

    <?php // echo $form->field($model, 'model_year') ?>

    <?php // echo $form->field($model, 'series') ?>

    <?php // echo $form->field($model, 'day_price') ?>

    <?php // echo $form->field($model, 'day_offer') ?>

    <?php // echo $form->field($model, 'week_price') ?>

    <?php // echo $form->field($model, 'week_offer') ?>

    <?php // echo $form->field($model, 'month_price') ?>

    <?php // echo $form->field($model, 'month_offer') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'sort_order') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
