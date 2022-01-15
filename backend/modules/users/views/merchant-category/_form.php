<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\MerchantCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body merchant-category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">



        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'name_ar')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'description_ar')->textarea(['rows' => 6]) ?>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'sort_order')->textInput() ?>

            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <div id="imagePriview">
                    <?php
                    if (isset($model->id) && $model->id > 0 && isset($model->image) && $model->image !== "") {
                        $imgPath = ((yii\helpers\Url::base())) . '/../uploads/merchant-category/' . $model->image;
                    } else {
                        $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                    }

                    echo '<img width="125" style="border: 2px solid #d2d2d2;" src="' . $imgPath . '" />';
                    ?>
                </div>
                <br/>
                <?php
                echo '<label class="control-label">Upload Merchant Category</label>';
                echo FileInput::widget([
                    'model' => $model,
                    'attribute' => 'image',
                    'options' => [
                        'id' => 'input-1',
//                        'multiple' => true
                    ],
                    'pluginOptions' => [
                        'showUpload' => false,
////                        'uploadUrl' => '/upload/create',
                        'allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
                    ]
                ]);
                ?>
                <span class="bmd-help"><?= Html::activeHint($model, 'image'); ?></span>

            </div>
        </div>

    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
