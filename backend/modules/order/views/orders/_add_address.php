<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$states = [];
$cities = [];
$area = [];
if ($addressmodel->isNewRecord) {

} else {
    if ($addressmodel->country != 0) {
        $get_states = \common\models\States::find()->where(['country_id' => $addressmodel->country])->all();
        $get_cities_query = \common\models\City::find()->where(['status' => 1, 'country' => $addressmodel->country]);
        $get_area_query = \common\models\Area::find()->where(['status' => 1]);
        if ($get_states != NULL) {
            foreach ($get_states as $get_state) {
                $states[$get_state->id] = $get_state->state_name;
            }
        }
        if ($addressmodel->state != 0) {
            $get_cities_query->andWhere(['state' => $addressmodel->state]);
        }
        $get_cities = $get_cities_query->all();
        if ($get_cities != NULL) {
            foreach ($get_cities as $get_city) {
                $cities[$get_city->id] = $get_city->name_en;
            }
        }
    }
}
?>
<div class="<?= $id; ?> info_panel_data">
    <div class="row">
        <?php if ($id == "shipping_address") { ?>
            <div class="col-sm-12">
                <div class="form-check form-switch my-auto">
                    <label class="same_as_billing_label">
                        <input  type="checkbox" class="same_as_billing">
                        Same As Billing Address
                    </label>
                </div>
            </div>
        <?php } ?>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($addressmodel, '[' . $id . ']' . 'first_name')->textInput(['class' => 'form-control ' . $field_class]) ?>

            </div>
        </div>


        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($addressmodel, '[' . $id . ']' . 'last_name')->textInput(['maxlength' => true, 'class' => 'form-control ' . $field_class]) ?>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($addressmodel, '[' . $id . ']' . 'country')->dropDownList(ArrayHelper::map(\common\models\Country::find()->all(), 'id', 'country_name'), ['prompt' => '', 'class' => 'form-control select_country ' . $field_class]); ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($addressmodel, '[' . $id . ']' . 'state')->dropDownList($states, ['prompt' => 'Select State', 'class' => 'form-control select_state ' . $field_class]); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($addressmodel, '[' . $id . ']' . 'city')->dropDownList($cities, ['prompt' => 'Select City', 'class' => 'form-control select_city ' . $field_class]); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($addressmodel, '[' . $id . ']' . 'streat_address')->textarea(['rows' => 2, 'class' => 'form-control ' . $field_class]) ?>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($addressmodel, '[' . $id . ']' . 'postcode')->textInput(['class' => 'form-control ' . $field_class]) ?>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($addressmodel, '[' . $id . ']' . 'phone_number')->textInput(['class' => 'form-control ' . $field_class]) ?>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($addressmodel, '[' . $id . ']' . 'email')->textInput(['class' => 'form-control ' . $field_class]) ?>

            </div>
        </div>

    </div>
</div>

<script>


</script>
<?php
$this->registerJs(<<< EOT_JS_CODE
        $(document).ready(function(){
 $(document.body).on("click", ".same_as_billing", function (e) {
            if($(this).prop('checked') == true){
                $( ".bill_field" ).each(function( index ) {
                 var field_value = $(this).val();
                 let field_name = $(this).attr('name');

               var result_name =  field_name.replace("UserAddress[billing_address]", "UserAddress[shipping_address]");

                console.log(result_name+" : " +field_value);

               $('[name="' + result_name + '"]').val(field_value);
                });
            }else{
$(".ship_field").each(function (index) {
        $(this).val("");
    });
            }
        });
        });



EOT_JS_CODE
);
?>