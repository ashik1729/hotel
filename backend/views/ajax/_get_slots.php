<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\Merchant */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$intervals = [];
if ($available_from != NULL && $available_to != NULL && $interval != NULL) {
    $intervals = Yii::$app->ManageRequest->getTimeSlots($available_from, $available_to, $interval, $disable_slots);
}
?>
<?php if ($intervals != NULL) { ?>
    <div class="row p-0">
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <div class="form-group field-productsservices-requires_shipping required has-success">
                    <label class="control-label" for="productsservices-requires_shipping">Booking Slot</label>
                    <select required="" id="productsservices-requires_shipping" class="form-control" name="OrderProducts[booking_slot]" aria-required="true" aria-invalid="false">
                        <option value="">Choose One Slot</option>

                        <?php
                        foreach ($intervals as $key => $val) {

                            if ($key < array_key_last($intervals)) {
                                ?>

                                <?php
                                $check_disable = common\models\DisableSlots::find()->where(['day' => $get_slotmodel->id, 'merchant_id' => $get_slotmodel->merchant_id, 'status' => 1, 'slot_from' => date('H:i', strtotime($intervals[$key])), 'slot_to' => date('H:i', strtotime($intervals[$key + 1]))])->one();
                                $checked = "";
                                if ($check_disable == NULL) {
                                    ?>
                                    <option value="<?php echo $intervals[$key]; ?> - <?php echo $intervals[$key + 1]; ?>" ><?php echo $intervals[$key]; ?> - <?php echo $intervals[$key + 1]; ?></option>
                                <?php }
                                ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </select>

                <div class="help-block"></div>
            </div>
        </div>
    </div>
</div>