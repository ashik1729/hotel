<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VisaFaq */
/* @var $form yii\widgets\ActiveForm */

use kartik\select2\Select2;
?>

<div class="card-body visa-faq-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">

                <label class="control-label" for="productsservices-merchant_id">Visa
                </label>
                <?php
                //  $datas = [];
                $options = array();

                $datas = \common\models\Visa::find()->all();
                if ($datas != NULL) {
                    foreach ($datas as $data) {
                       
                            $options[$data->id] = $data->title;
                        
                    }
                }
                //                        }
                ?>
                <?php
                echo Select2::widget([
                    'model' => $model,
                    'attribute' => 'visa_id',
                    'data' => $options,
                    'theme' => Select2::THEME_MATERIAL,
                    'options' => ['placeholder' => 'Select a  Visa.', 'class' => 'change_category'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        // 'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 20
                    ],
                ]);
                ?>
              

            </div>
        </div>



        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'question')->textarea(['rows' => 2]) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'answer')->textarea(['rows' => 6]) ?>

            </div>
        </div>


        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>
            </div>
        </div>

        <div class="col-sm-12">
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