<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use dosamigos\ckeditor\CKEditor;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\ErrorCode */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="card-body error-code-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>




    <div class="row">

        <div class="col-sm-12">

            <?php
            echo $form->field($model, 'import')->widget(FileInput::classname(), [
                'options' => [
//                        'multiple' => true
                    'id' => 'input-2',
                ],
                'pluginOptions' => [
                    'showUpload' => false,
                    'allowedFileExtensions' => ['xlsx', 'xlsm'],
                ]
            ]);
            ?>

        </div>

    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
