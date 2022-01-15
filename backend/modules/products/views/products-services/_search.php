<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProductsServicesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-services-search search_form">

    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>
    <div class="data-filters">
        <div class="row">
            <div class="col-sm-2">
                <?= $form->field($model, 'type')->dropDownList(['1' => 'Product', '2' => 'Shop Service', '3' => 'Home Service']) ?>
            </div>

            <?php
            $merchant = [];

//            $get_merchants = \common\models\Merchant::find()->where(['status' => 10])->all();
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
//                    $merchant[$get_merchant->id] = $get_merchant->first_name . ' ' . $get_merchant->last_name . '(' . $get_merchant->country0->country_name . ')';
                    $merchant[$get_merchant->id] = $get_merchant->business_name . '(' . $get_merchant->email . ')';
                }
            }
            ?>


            <div class="col-sm-3">

                <div class="form-group bmd-form-group">
                    <label class="control-label" for="productsservices-merchant_id">Merchant
                    </label>
                    <?php
                    echo kartik\select2\Select2::widget([
                        'model' => $model,
                        'attribute' => 'merchant_id',
                        'data' => $merchant,
                        'disabled' => $disable,
                        'theme' => kartik\select2\Select2::THEME_MATERIAL,
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
            <div class="col-sm-2">
                <?= $form->field($model, 'stock_availability')->dropDownList(['1' => 'Yes', '0' => 'No']) ?>

            </div>

            <div class="col-sm-2">
                <?= $form->field($model, 'is_admin_approved')->dropDownList(['1' => 'Yes', '0' => 'No']) ?>

            </div>

            <div class="col-sm-1">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>

            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
                </div>
            </div>

        </div>
    </div>



    <?php ActiveForm::end(); ?>

</div>
