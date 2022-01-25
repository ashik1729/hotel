<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use dosamigos\ckeditor\CKEditorInline;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model common\models\Visa */
/* @var $form yii\widgets\ActiveForm */

use kartik\select2\Select2;

$this->registerJs("CKEDITOR.plugins.addExternal('pbckcode', '/pbckcode/plugin.js', '');");

?>

<div class="card-body visa-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
       
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'short_description')->widget(CKEditor::className(), [
                    'options' => ['rows' => 6],
                    'preset' => 'custom'    
                ]) ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'long_description')->widget(CKEditor::className(), [
                    'options' => ['rows' => 6],
                    'preset' => 'custom'
                ]) ?>


            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <div id="imagePriview">
                    <?php
                    if (isset($model->id) && $model->id > 0 && isset($model->image) && $model->image !== "") {
                        $imgPath = ((yii\helpers\Url::base())) . '/../uploads/visa/' . $model->id . '/image/' . $model->image;
                    } else {
                        $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                    }
                    echo '<img width="125" style="border: 2px solid #d2d2d2;" src="' . $imgPath . '" />';
                    ?>
                </div>
                <br />
                <?php
                echo '<label class="control-label">Upload Visa Banner Image</label>';
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
                <div id="imagePriview">
                    <?php
                    $images = explode(',', $model->gallery);
                    $result_html = '';
                    if ($images != NULL) {
                        foreach ($images as $image) {
                            if (isset($model->id) && $model->id > 0 && isset($model->gallery) && $model->gallery !== "") {
                                $imgPath = ((yii\helpers\Url::base())) . '/../uploads/visa/' . $model->id . '/gallery/' . $image;
                            } else {
                                $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                            }
                            $delete_url = Yii::$app->request->baseUrl . '/visa/gallery-delete?id=' . $model->id . '&item=' . $image;
                            $result_html .= '<div class ="img_gallery"><a href="' . $delete_url . '"><img width="125" style="border: 2px solid #d2d2d2;" src="' . $imgPath . '" /><i class="fa fa-trash trash_file"></i></a></div>';
                        }
                        echo $result_html;
                    }
                    ?>
                    <div class="clearfix"></div>
                </div>
                <br />
                <?php
                echo '<label class="control-label">Upload Business Gallery (If any)</label>';
                echo FileInput::widget([
                    'model' => $model,
                    'attribute' => 'gallery',
                    'options' => [
                        'id' => 'input-3',
                        'multiple' => true
                    ],
                    'pluginOptions' => [
                        'showUpload' => false,
                        'allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
                    ]
                ]);
                ?>
                <span class="bmd-help"><?= Html::activeHint($model, 'gallery'); ?></span>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>
            </div>
        </div>


        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'whatsapp_no')->textInput() ?>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'phone_no')->textInput() ?>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'email')->textInput() ?>

            </div>
        </div>
        <?php $visaOptions = \common\models\VisaOption::find(['status' => 1])->all();
        $visas = [];
        if ($visaOptions != NULL) {
            foreach ($visaOptions as $visaOption) {
                $visas[$visaOption->id] = $visaOption->title;
            }
        }
        ?>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <label class="control-label" for="merchant-email">Visa Options</label>
                <?php
                if (isset($model->id) && $model->id > 0) {
                    if ($model->visa_option != '') {
                        $model->visa_option = explode(',', $model->visa_option);
                    }
                }
                echo Select2::widget([
                    'model' => $model,
                    'attribute' => 'visa_option',
                    'data' => $visas,
                    'theme' => Select2::THEME_MATERIAL,
                    'options' => ['placeholder' => 'Visa Options.', 'multiple' => true],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 255
                    ],
                ]);
                ?>

            </div>
        </div>


        <?php $processingTypes = \common\models\ProcessingType::find(['status' => 1])->all();
        $processings = [];
        if ($processingTypes != NULL) {
            foreach ($processingTypes as $processingType) {
                $processings[$processingType->id] = $processingType->title;
            }
        }
        ?>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <label class="control-label" for="merchant-email">Visa Options</label>
                <?php
                if (isset($model->id) && $model->id > 0) {
                    if ($model->processing_type != '') {
                        $model->processing_type = explode(',', $model->processing_type);
                    }
                }
                echo Select2::widget([
                    'model' => $model,
                    'attribute' => 'processing_type',
                    'data' => $processings,
                    'theme' => Select2::THEME_MATERIAL,
                    'options' => ['placeholder' => 'Processing Type.', 'multiple' => true],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 255
                    ],
                ]);
                ?>

            </div>
        </div>
        
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'sort_order')->textInput() ?>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'price')->textInput() ?>

            </div>
        </div>


    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>