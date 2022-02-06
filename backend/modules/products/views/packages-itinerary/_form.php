<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model common\models\PackagesItinerary */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
    $packages = \common\models\ProductsServices::find()->where(['status' => 1])->all();
    $options = array();
    if ($packages != NULL) {
        foreach ($packages as $package) {
            if (!empty($package)) {
                $options[$package->id] = $package->package_title;
            }
        }
    }
    ?>

<div class="card-body packages-itinerary-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?php // $form->field($model, 'package_id')->textInput()
                            echo Select2::widget([
                                'model' => $model,
                                'attribute' => 'package_id',
                                'data' => $options,
                                'theme' => Select2::THEME_MATERIAL,
                                'options' => ['placeholder' => 'Select Package', 'class' => 'packageID'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'tokenSeparators' => [',', ' '],
                                    'maximumInputLength' => 20
                                ],
                            ]); 
                            
                            ?>
                            <?php // $form->field($model, 'package_id')->dropDownList($options) ?>

                    </div>
                </div>
                
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    </div>
                </div>
                
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
                                    'options' => ['rows' => 6],
                                    'preset' => 'custom'
                                ]) ?>

                    </div>
                </div>
                
                
                    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
