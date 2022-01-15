<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="modal fade" id="order_edit" tabindex="-1" role="dialog" aria-labelledby="editAttributeValueModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAttributeValueModalTitle">Update Payments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php $form = ActiveForm::begin(['options' => ['class' => 'form_update_order'], 'action' => 'update-order-details?id=' . $model->id]); ?>
            <div class="modal-body">
                <div class="product_history"></div>
                <p class="attr_error"></p>
                <div class="card-body orders-form">

                    <div class="row">


                        <div class="col-sm-4">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'payment_method')->dropDownList(['1' => 'Cash', '2' => 'Card'], ['prompt' => 'Select Payment Method', 'class' => 'form-control']) ?>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'transaction_id')->textInput(['maxlength' => true]) ?>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'payment_status')->dropDownList(['0' => 'Pending', '1' => 'Success', '2' => 'Failed'], ['prompt' => 'Select Payment Status', 'class' => 'form-control']) ?>
                            </div>
                        </div>



                    </div>



                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary ">Save </button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

