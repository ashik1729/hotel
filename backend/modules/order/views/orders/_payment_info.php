<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="<?= $id; ?> info_panel_data">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($order, 'status')->dropDownList(ArrayHelper::map(\common\models\OrderStatus::find()->all(), 'id', 'name'), ['prompt' => 'Choose Order Status', 'class' => 'form-control '])->label("Order Status"); ?>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($order, 'shipping_method')->dropDownList(['1' => 'Home Service', '2' => 'Shop Service'], ['prompt' => 'Choose Delivery Method']) ?>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($order, 'payment_method')->dropDownList(['1' => 'Card', '2' => 'Cash On Delivery', '3' => 'Online Pay'], ['prompt' => 'Choose Status']) ?>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($order, 'payment_status')->dropDownList(['0' => 'Pending', '1' => 'Success', '2' => 'Failed'], ['prompt' => 'Choose Status']) ?>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($order, 'transaction_id')->textInput(['maxlength' => true]) ?>

            </div>
        </div>



    </div>
</div>

