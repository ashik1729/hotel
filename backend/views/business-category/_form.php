<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Modal;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessCategory */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$datas = \common\models\BusinessCategory::find()->all();
$options = array();
if ($datas != NULL) {
    foreach ($datas as $data) {

        if (!empty($data)) {
            $option_items = Yii::$app->SelectCategory->selectBusinessCategories($data);
            $option_data = explode('-', $option_items);
            $option_data_array = array_reverse($option_data);
            $latest_option = [];
            if ($option_data_array != NULL) {
                foreach ($option_data_array as $option_data_arr) {
                    $option_cat = \common\models\BusinessCategory::find()->where(['id' => $option_data_arr])->one();
                    $latest_option[] = $option_cat->title;
                }
            }

            $option_text = implode(' -> ', $latest_option);

            $options[$data->id] = $option_text;
        }
    }
}
?>
<div class="card-body business-category-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">


        <!--        <div class="col-sm-3">
                    <div class="form-group bmd-form-group">-->

        <?php
//                echo Select2::widget([
//                    'model' => $model,
//                    'attribute' => 'parent',
//                    'data' => $options,
//                    'theme' => Select2::THEME_MATERIAL,
//                    'options' => ['placeholder' => 'Select a Parent Category.'],
//                    'pluginOptions' => [
//                        'allowClear' => true,
//                        'tokenSeparators' => [',', ' '],
//                        'maximumInputLength' => 20
//                    ],
//                ]);
        ?>
        <!--            </div>
                </div>-->




        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'category_name_en')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'category_name_ar')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>
            </div>
        </div>


    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
