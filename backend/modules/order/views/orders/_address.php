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
<div class="modal fade" id="<?php echo $name; ?>" tabindex="-1" role="dialog" aria-labelledby="editAttributeValueModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAttributeValueModalTitle">Update Address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php $form = ActiveForm::begin(['options' => ['class' => 'form_update_address'], 'action' => 'update-user-address?id=' . $model->id . '&type=' . $type]); ?>
            <div class="modal-body">
                <div class="product_history"></div>
                <p class="attr_error"></p>
                <div class="card-body orders-form">

                    <div class="row">


                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($addressmodel, 'first_name')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($addressmodel, 'last_name')->textInput(['maxlength' => true]) ?>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($addressmodel, 'country')->dropDownList(ArrayHelper::map(\common\models\Country::find()->all(), 'id', 'country_name'), ['prompt' => '', 'class' => 'form-control select_country']); ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($addressmodel, 'state')->dropDownList($states, ['prompt' => 'Select State', 'class' => 'form-control select_state']); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($addressmodel, 'city')->dropDownList($cities, ['prompt' => 'Select City', 'class' => 'form-control select_city']); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($addressmodel, 'streat_address')->textarea(['rows' => 2]) ?>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($addressmodel, 'postcode')->textInput() ?>

                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($addressmodel, 'phone_number')->textInput() ?>

                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($addressmodel, 'email')->textInput() ?>
                                <?= $form->field($addressmodel, 'id')->hiddenInput()->label(FALSE) ?>

                            </div>
                        </div>

                    </div>



                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary save_update_history">Save </button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

