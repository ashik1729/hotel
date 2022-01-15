<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Modal;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\ImageAssets */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body image-assets-form">
    <?php
    $franchise = [];

    $get_franchises = \common\models\Franchise::find()->where(['status' => 10])->all();
    if ($get_franchises != NULL) {
        foreach ($get_franchises as $get_franchise) {
            $franchise[$get_franchise->id] = $get_franchise->first_name . ' ' . $get_franchise->first_name . '(' . $get_franchise->country0->country_name . ')';
        }
    }
    ?>
    <?php
    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data',
                    'encodeErrorSummary' => false,
                    'errorSummaryCssClass' => 'help-block',]]);
    ?>

    <div class="row">
        <div class="col-xs12">    <?= $form->errorSummary($model) ?>

        </div>
        <?php
        $image_type = [];

        $get_image_types = \common\models\ImageType::find()->where(['status' => 1])->all();
        if ($get_image_types != NULL) {
            foreach ($get_image_types as $get_image_type) {
                $image_type[$get_image_type->id] = $get_image_type->title;
            }
        }
        ?>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?php
                echo Select2::widget([
                    'model' => $model,
                    'attribute' => 'type',
                    'data' => $image_type,
                    'theme' => Select2::THEME_MATERIAL,
                    'options' => ['placeholder' => 'Select a  Image Type.'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 20
                    ],
                ]);
                ?>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'device_type')->dropDownList(['1' => 'Android', '2' => 'Ios'], ['prompt' => 'Choose Device']) ?>

            </div>
        </div>
        <div class="col-sm-12">

            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'store_id')->dropDownList($franchise, ['prompt' => 'Choose A Franchise', 'class' => 'form-control select_country']); ?>

            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <div id="imagePriview">
                    <?php
                    if (isset($model->id) && $model->id > 0 && isset($model->image) && $model->image !== "") {
                        $imgPath = ((yii\helpers\Url::base())) . '/../uploads/filemanagement/' . base64_encode($model->id) . '/' . $model->image;
                    } else {
                        $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                    }

                    echo '<img width="125" style="border: 2px solid #d2d2d2;" src="' . $imgPath . '" />';
                    ?>
                </div>
                <br/>
                <?php
                echo '<label class="control-label">Upload  Image</label>';
                echo FileInput::widget([
                    'model' => $model,
                    'attribute' => 'image',
                    'options' => [
//                        'multiple' => true
                        'id' => 'input-2',
                    ],
                    'pluginOptions' => [
                        'showUpload' => false,
                        'allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
                    ]
                ]);
                ?>
                <span class="bmd-help"><?= Html::activeHint($model, 'image'); ?></span>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'description_en')->textarea(['rows' => 3]) ?>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'description_ar')->textarea(['rows' => 3]) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>

            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'sort_order')->textInput(['maxlength' => true, 'value' => 0]) ?>

            </div>
        </div>

    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
