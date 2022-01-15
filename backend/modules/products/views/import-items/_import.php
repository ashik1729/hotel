<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use dosamigos\ckeditor\CKEditor;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\ErrorCode */
/* @var $form yii\widgets\ActiveForm */
?>

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
        $merchant[$get_merchant->id] = $get_merchant->first_name . ' ' . $get_merchant->last_name . '(' . $get_merchant->country0->country_name . ')';
    }
}
?>
<div class="card-body error-code-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>




    <div class="row">

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <label class="control-label" for="productsservices-merchant_id">Merchant
                </label>
                <?php
                echo Select2::widget([
                    'model' => $model,
                    'attribute' => 'merchant_id',
                    'data' => $merchant,
                    'readonly' => $disable,
                    'theme' => Select2::THEME_MATERIAL,
                    'options' => ['placeholder' => 'Select a  Merchant.', 'class' => 'merchant_change'],
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

            <?php
            echo $form->field($model, 'file')->widget(FileInput::classname(), [
                'options' => [
//                        'multiple' => true
                    'id' => 'input-2',
                ],
                'pluginOptions' => [
                    'showUpload' => false,
                    'allowedFileExtensions' => ['xlsx', 'xlsm'],
                ]
            ]);
            ?>

        </div>

    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
