<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProductViewed */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body product-viewed-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'product_id')->textInput() ?>

                    </div>
                </div>
                
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'session_token')->textInput(['maxlength' => true]) ?>

                    </div>
                </div>
                
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'user_id')->textInput() ?>

                    </div>
                </div>
                    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
