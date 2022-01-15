<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Modal;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\SystemConfiguration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body system-configuration-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">




        <div class="col-sm-6">
            <div class="form-group bmd-form-group">


                <?php
                echo Select2::widget([
                    'model' => $model,
                    'attribute' => 'language',
                    'data' => ArrayHelper::map(\common\models\Language::find()->all(), 'id', 'name'),
                    'theme' => Select2::THEME_MATERIAL,
                    'options' => [
                        'placeholder' => 'Select  Language.',
                        'multiple' => true
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
//                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 20
                    ],
                ]);
                ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">


                <?php
                echo Select2::widget([
                    'model' => $model,
                    'attribute' => 'currency',
                    'data' => ArrayHelper::map(\common\models\Currency::find()->all(), 'id', 'name'),
                    'theme' => Select2::THEME_MATERIAL,
                    'options' => [
                        'placeholder' => 'Select  Currency.',
                        'multiple' => true
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
//                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 20
                    ],
                ]);
                ?>
            </div>
        </div>

    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
