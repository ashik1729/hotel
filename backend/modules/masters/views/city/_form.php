<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\City */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$states = [];
if ($model->isNewRecord) {

} else {
    if ($model->country != 0) {
        $get_states = \common\models\States::find()->where(['country_id' => $model->country])->all();
        if ($get_states != NULL) {
            foreach ($get_states as $get_state) {
                $states[$get_state->id] = $get_state->state_name;
            }
        }
    }
}
?>
<div class="card-body city-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'country')->dropDownList(ArrayHelper::map(\common\models\Country::find()->all(), 'id', 'country_name'), ['prompt' => '', 'class' => 'form-control select_country']); ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'state')->dropDownList($states, ['prompt' => 'Select State', 'class' => 'form-control select_state']); ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'name_ar')->textInput(['maxlength' => true]) ?>

            </div>
        </div>



        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'sort_order')->textInput(['value' => '0']) ?>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable'], ['prompt' => 'Choose Status']) ?>

            </div>
        </div>


        <div class="col-sm-12 area_list">
            <div class="area_content">
                <?=
                $this->render(
                        '_area_form.php', ['areamodel' => $areamodel, 'model' => $model, 'form' => $form]
                )
                ?>

            </div>
        </div>
    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs(<<< EOT_JS_CODE

$('.select_country').change(function(){
            var country_id = $(this).val();
            $.ajax({
                type: "POST",
                url: baseurl + "/users/franchise/get-states",
                data: {country_id: country_id}
            }).done(function (data) {
                    $('.select_state').html(data);
            });

        });


EOT_JS_CODE
);
?>
