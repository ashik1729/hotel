<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Area */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$get_areas = [];
if (!$model->isNewRecord) {
    $get_areas = \common\models\Area::find()->where(['city' => $model->id])->all();
}
?>

<div class="card-body area-form">
    <h5 class="card-title font-weight-bold hidden">Area</h5>

    <div class="area_form_data_ref " style="display: none">
        <div class="row area_row">
            <div class="col-sm-3">
                <div class="form-group bmd-form-group">
                    <?= $form->field($areamodel, 'name_en[]')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($areamodel, 'id[]')->textInput(['maxlength' => true, 'class' => 'form-control hidden_field area-id'])->label(FALSE) ?>

                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group bmd-form-group">
                    <?= $form->field($areamodel, 'name_ar[]')->textInput(['maxlength' => true]) ?>

                </div>
            </div>


            <div class="col-sm-2">
                <div class="form-group bmd-form-group">
                    <?= $form->field($areamodel, 'status[]')->dropDownList(['1' => 'Enable', '0' => 'Disable'], ['prompt' => 'Choose Status']) ?>

                </div>
            </div>



            <div class="col-sm-2">
                <div class="form-group bmd-form-group">
                    <?= $form->field($areamodel, 'sort_order[]')->textInput(['value' => 0]) ?>

                </div>
            </div>
            <div class="col-sm-2">
                <div class="action_wrapper">
                    <a class="add add_btn_actions" title="" data-toggle="tooltip" data-original-title="Add"><i class="material-icons"></i></a>
                    <a class="delete delete_btn_actions" title="" data-toggle="tooltip" data-original-title="Delete"><i class="material-icons"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="area_form_data">
        <?php if ($get_areas == NULL) { ?>
            <a class="add add_btn_actions add_new_btn_actions" title="" data-toggle="tooltip" data-original-title="Add"><i class="material-icons"></i></a>
        <?php } ?>
        <?php
        if (!$model->isNewRecord && $get_areas != NULL) {
            foreach ($get_areas as $get_area) {
                ?>
                <div class="row area_row">
                    <div class="col-sm-3">
                        <div class="form-group bmd-form-group">
                            <?= $form->field($areamodel, 'name_en[]')->textInput(['maxlength' => true, 'value' => $get_area->name_en]) ?>
                            <?= $form->field($areamodel, 'id[]')->textInput(['maxlength' => true, 'value' => $get_area->id, 'class' => 'form-control hidden_field area-id'])->label(FALSE) ?>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group bmd-form-group">
                            <?= $form->field($areamodel, 'name_ar[]')->textInput(['maxlength' => true, 'value' => $get_area->name_ar]) ?>

                        </div>
                    </div>


                    <div class="col-sm-2">
                        <div class="form-group bmd-form-group">
                            <?= $form->field($areamodel, 'status[]')->dropDownList(['1' => 'Enable', '0' => 'Disable'], ['prompt' => 'Choose Status', 'value' => $get_area->status]) ?>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group bmd-form-group">
                            <?= $form->field($areamodel, 'sort_order[]')->textInput(['value' => $get_area->sort_order]) ?>

                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="action_wrapper">
                            <a class="add add_btn_actions" title="" data-toggle="tooltip" data-original-title="Add"><i class="material-icons"></i></a>
                            <a class="delete delete_btn_actions" title="" data-toggle="tooltip" data-original-title="Delete"><i class="material-icons"></i></a>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>



</div>
<?php
$this->registerJs(<<< EOT_JS_CODE

var area_form_content = $('.area_form_data_ref').html();
$('.area_form_data_ref').remove();


    $(document.body).on('click','.add_btn_actions',function(){
         $(".add_new_btn_actions").remove();
        $('.area_form_data').append(area_form_content);
        });
  $(document.body).on('click','.delete_btn_actions',function(){
        var id = $(this).closest('.area_row').find('.area-id').val();
        if (confirm('Are You Sure to delete thia Area')) {

        } else {
           return false;
        }
        if(id != ""){
     $.ajax({
                type: "POST",
                url: baseurl + "/masters/city/delete-area",
                data: {id: id}
            }).done(function (data) {

            });
        }
            $(this).closest('.area_row').remove();
        var current_form_content = $('.area_form_data').html();

                   if (!$.trim( $(".area_form_data").html()) == true){

                $('.area_form_data').append('<a class="add add_btn_actions add_new_btn_actions" title="" data-toggle="tooltip" data-original-title="Add"><i class="material-icons"></i></a>');
            }
        });



EOT_JS_CODE
);
?>
