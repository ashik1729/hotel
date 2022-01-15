<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>



<?php $formone = ActiveForm::begin(['id' => 'form_update_product', 'options' => ['class' => 'form_update_product'], 'action' => $url]); ?>

<div class="modal-body update_product_body">
    <div class="product_history"></div>
    <p class="attr_error"></p>
    <div class="card-body orders-form">

        <div class="row">


            <div class="col-sm-12">
                <div class="form-group bmd-form-group">
                    <?= $formone->field($model, 'product_id')->dropDownList([$model->product_id => $model->product->product_name_en], ['class' => 'form-control update_product_id', 'readonly' => true]) ?>
                    <?= $formone->field($model, 'id')->hiddenInput()->label(FALSE) ?>

                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group bmd-form-group">
                    <?= $formone->field($model, 'quantity')->textInput(['maxlength' => true]) ?>

                </div>
            </div>
            <div class=" col-sm-12">
                <div class="booking_date "  >
                    <?= $formone->field($model, 'date')->textInput(['type' => 'date', 'id' => 'calender', 'class' => 'change_date_for_product form-control']) ?>

                </div>
                <div class="booking_slots ">
                    <?php
                    $product_id = $model->product_id;
                    $product = \common\models\ProductsServices::find()->where(['id' => $model->product_id])->one();
                    $product_type = $product->type;


                    $data_slots = '';
                    if ($model->date) {
                        if ($product->type == 2 || $product->type == 3) {
                            $day = date('l', strtotime($model->date));
                            $get_week_day = \common\models\WeekDaysAvailability::find()->where(['merchant_id' => $product->merchant_id, 'day' => $day, 'availability' => 1])->one();
                            if ($get_week_day != NULL) {

                                $get_disable_slots = \common\models\DisableSlots::find()->where("slot_from >= '" . $get_week_day->available_from . "' AND slot_to <= '" . $get_week_day->available_to . "'")->andWhere(['merchant_id' => $product->merchant_id, 'day' => $get_week_day->id])->all();
                                $booking_slot = $model->booking_slot;
                                echo $this->render('_get_slots', [
                                    'disable_slots' => $get_disable_slots,
                                    'available_from' => $get_week_day->available_from,
                                    'available_to' => $get_week_day->available_to,
                                    'interval' => $get_week_day->slot_interval,
                                    'get_slotmodel' => $get_week_day,
                                    'booking_slot' => $booking_slot,
                                ]);
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="col-sm-12">

                <?php
                $options = explode(',', $model->options);
                if ($attributes != NULL) {
                    ?>
                    <?php foreach ($attributes as $attribute) {
                        ?>

                        <div class="row  p-0">
                            <?php if ($attribute['attr_items'] != NULL) { ?>
                                <div class="col-sm-12">
                                    <label class="control-label" for="orderproducts-option"><?php echo $attribute['attribute_name']; ?></label>
                                </div>
                                <?php
                                foreach ($attribute['attr_items'] as $att_item) {
                                    $checked = "";
                                    if (in_array($att_item['id'], $options)) {
                                        $checked = "checked";
                                    }
                                    ?>
                                    <div class="col-sm-3">

                                        <label class="action_label">
                                            <input type="radio" id="css" <?php echo $checked; ?> required=""  name="OrderProducts[attribute][<?php echo $attribute['attribute_id']; ?>]" value="<?= $att_item['id']; ?>"> &nbsp;&nbsp;<?= $att_item['attributes_value']; ?>
                                        </label>
                                        <?php $formone->field($model, 'options[' . $attribute['attribute_id'] . ']')->radioList([$att_item['id'] => $att_item['attributes_value']], ['checked' => $checked])->label(FALSE); ?>
                                        <br>
                                    </div>
                                <?php } ?>

                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>


            </div>




        </div>



    </div>


</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" fo class="btn btn-primary form_update_product_btn" form="form_update_product">Save </button>

</div>
<?php ActiveForm::end(); ?>

