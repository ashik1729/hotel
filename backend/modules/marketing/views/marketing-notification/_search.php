<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MarketingNotificationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="marketing-notification-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title_ar') ?>

    <?= $form->field($model, 'title_en') ?>

    <?= $form->field($model, 'description_en') ?>

    <?= $form->field($model, 'description_ar') ?>

    <?php // echo $form->field($model, 'file') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'user_group') ?>

    <?php // echo $form->field($model, 'user') ?>

    <?php // echo $form->field($model, 'link') ?>

    <?php // echo $form->field($model, 'notification_type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
