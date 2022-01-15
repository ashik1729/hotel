<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Modal;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\MerchantFeatureList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body merchant-feature-list-form">

    <?php
    $form = ActiveForm::begin(['encodeErrorSummary' => false,
                'errorSummaryCssClass' => 'help-block']);
    ?>
    <div class="row">
        <div class="col-xs-12">
            <?= $form->errorSummary($model) ?>
            <?= $form->errorSummary($modelfet) ?>
        </div>
    </div>
    <div class="row">

        <?php
        $merchant = [];
        if (\Yii::$app->user->identity->interface == 'merchant') {
            $get_merchants = \common\models\Merchant::find()->where(['status' => 10, 'id' => \Yii::$app->user->identity->id])->all();
            $model->merchant_id = \Yii::$app->user->identity->id;
            $disable = TRUE;
        } else {
            $disable = FALSE;
            $get_merchants = \common\models\Merchant::find()->where(['status' => 10])->all();
        }
        if ($get_merchants != NULL) {
            foreach ($get_merchants as $get_merchant) {
                $merchant[$get_merchant->id] = $get_merchant->first_name . ' ' . $get_merchant->last_name . '(' . $get_merchant->country0->country_name . ')';
            }
        }
        ?>

        <?php
        $featurelist = [];

        if (\Yii::$app->user->identity->interface == 'merchant') {

            $get_feature_lists = \common\models\FeaturesList::find()->all();
        } else {
            $get_feature_lists = \common\models\FeaturesList::find()->all();
        }
        if ($get_feature_lists != NULL) {
            foreach ($get_feature_lists as $get_feature_list) {
                $featurelist[$get_feature_list->id] = $get_feature_list->title;
            }
        }
        ?>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <label class="control-label" for="productsservices-merchant_id">Merchant
                </label>
                <?php
                echo Select2::widget([
                    'model' => $model,
                    'attribute' => 'merchant_id',
                    'data' => $merchant,
                    'readonly' => true,
//                    'value' => $merchant  ,
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

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <label>Feature Name</label>
                <?php
                echo Select2::widget([
                    'model' => $model,
//                    'name' => 'feature_id',
                    'attribute' => 'feature_id',
                    'data' => $featurelist,
//                    'value' => $featurelist,
                    'theme' => Select2::THEME_MATERIAL,
                    'options' => ['placeholder' => 'Choose Feature ...'],
                    'maintainOrder' => true,
                    'pluginOptions' => [
                        'allowClear' => true,
                        'tags' => true,
                        'tokenSeparators' => [','],
                        'maximumInputLength' => 20
                    ],
                ]);
                ?>
            </div>


        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'value_en')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'value_ar')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'sort_order')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable'], ['disabled' => $disable]) ?>

            </div>
        </div>

    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
