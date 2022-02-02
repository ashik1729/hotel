<?php

use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\CmsData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body cms-data-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">


        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'page_id')->dropDownList(ArrayHelper::map(\common\models\CmsContent::find()->all(), 'id', 'title'), ['prompt' => 'Choose Page', 'class' => 'form-control select_event_country'])->label("Page"); ?>
            </div>
        </div>


        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <div id="imagePriview">
                    <?php
                    if (isset($model->id) && $model->id > 0 && isset($model->file) && $model->file !== "") {
                        $imgPath = ((yii\helpers\Url::base())) . '/../uploads/cms-data/' . $model->id . '/file/' . $model->file;
                    } else {
                        $imgPath = "";
                    }
                    echo '<a href="' . $imgPath . '" width="125" style="border: 2px solid #d2d2d2;" >View File </a>';
                    ?>
                </div>
                <br />
                <?php
                echo '<label class="control-label">Upload category Image</label>';
                echo FileInput::widget([
                    'model' => $model,
                    'attribute' => 'file',
                    'options' => [
                        //                        'multiple' => true
                        'id' => 'input-2',
                    ],
                    'pluginOptions' => [
                        'showUpload' => false,
                        'allowedFileExtensions' => ['jpg', 'jpeg', 'png','mp4','ogg','webp'],
                    ]
                ]);
                ?>
                <span class="bmd-help"><?= Html::activeHint($model, 'image'); ?></span>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <div id="imagePriview">
                    <?php
                    $images = explode(',', $model->gallery);
                    $result_html = '';
                    if ($images != NULL) {
                        foreach ($images as $image) {
                            if (isset($model->id) && $model->id > 0 && isset($model->gallery) && $model->gallery !== "") {
                                $imgPath = ((yii\helpers\Url::base())) . '/../uploads/cms-data/' . $model->id . '/gallery/' . $image;
                            } else {
                                $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                            }
                            $delete_url = Yii::$app->request->baseUrl . '/cms-content/gallery-delete?id=' . $model->id . '&item=' . $image;
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
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
               
                <?= $form->field($model, 'field_one')->widget(CKEditor::className(), [
                    // 'options' => ['rows' => 3],
                    'preset' => 'custom'
                ]) ?>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                
            <?= $form->field($model, 'field_two')->widget(CKEditor::className(), [
                    // 'options' => ['rows' => 3],
                    'preset' => 'custom'
                ]) ?>
               
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
            <?= $form->field($model, 'field_three')->widget(CKEditor::className(), [
                    // 'options' => ['rows' => 3],
                    'preset' => 'custom'
                ]) ?>
             
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
            <?= $form->field($model, 'field_four')->widget(CKEditor::className(), [
                    // 'options' => ['rows' => 3],
                    'preset' => 'custom'
                ]) ?>
              
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
    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>