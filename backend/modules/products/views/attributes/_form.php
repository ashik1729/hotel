<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Modal;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Attributes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body attributes-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">


        <div class="col-sm-4">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>

            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'sort_order')->textInput(['value' => 0]) ?>

            </div>
        </div>
        <?php
        $attr_data = [];
        if ($model->isNewRecord) {
            $attr_data = [];
        } else {
            $get_attr_datas = common\models\AttributesValue::find()->where(['attributes_id' => $model->id])->all();
            if ($get_attr_datas != NULL) {
                foreach ($get_attr_datas as $get_attr_data) {
                    $attr_data[$get_attr_data->value] = $get_attr_data->value;
                    $attr_data1[$get_attr_data->value] = $get_attr_data->value;
                }
            }
        }
        ?>
        <div class="col-sm-12">

            <div class="form-group bmd-form-group">
                <label>Attribute Values</label>
                <?php
                echo Select2::widget([
                    'name' => 'attr_value',
                    'data' => $attr_data,
                    'value' => $attr_data1, // initial value (will be ordered accordingly and pushed to the top)
                    'theme' => Select2::THEME_MATERIAL,
                    'options' => ['placeholder' => 'Search Attribute Values ...', 'multiple' => true],
                    'maintainOrder' => true,
                    'pluginOptions' => [
                        'tags' => true,
                        'allowClear' => true,
                        'tokenSeparators' => [','],
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
