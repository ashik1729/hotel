<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Discounts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body discounts-form">
    <?php
    $merchant = [];
    if (\Yii::$app->user->identity->interface == 'merchant') {
        $get_merchants = \common\models\Merchant::find()->where(['status' => 10, 'id' => \Yii::$app->user->identity->id])->all();
        $model->merchant_id = \Yii::$app->user->identity->id;
        $disable = TRUE;
    } else if (\Yii::$app->user->identity->interface == 'franchise') {
        $disable = FALSE;
        $get_merchants = \common\models\Merchant::find()->where(['franchise_id' => \Yii::$app->user->identity->id])->all();
    } else {
        $disable = FALSE;
        $get_merchants = \common\models\Merchant::find()->where(['status' => 10])->all();
    }
    if ($get_merchants != NULL) {
        foreach ($get_merchants as $get_merchant) {
            $merchant[$get_merchant->id] = $get_merchant->business_name . '(' . $get_merchant->email . '-' . $get_merchant->country0->country_name . ')';
        }
    }
    ?>

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">


        <div class="col-sm-4">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'title_ar')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="col-sm-4">

            <div class="form-group bmd-form-group">
                <label class="control-label" for="productsservices-merchant_id">Merchant
                </label>
                <?php
                echo Select2::widget([
                    'model' => $model,
                    'attribute' => 'merchant_id',
                    'data' => $merchant,
                    'disabled' => $disable,
                    'theme' => Select2::THEME_MATERIAL,
                    'options' => ['placeholder' => 'Select a  Merchant.'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 20
                    ],
                ]);
                ?>
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

        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'discount_type')->dropDownList(['1' => 'Flat', '2' => 'Percentage'], ['prompt' => 'Choose Discount Type']) ?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'item_type')->dropDownList(['1' => 'Store/Merchant', '2' => 'Product/Service'], ['prompt' => 'Choose Item Type']) ?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'discount_rate')->textInput() ?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'coupon_code')->textInput() ?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'discount_from')->textInput(['type' => 'date']) ?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'discount_to')->textInput(['type' => 'date']) ?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>
            </div>
        </div>
        <div class="col-sm-3">
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
