<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AdminRole */
/* @var $form yii\widgets\ActiveForm */
$checked = "";
?>
<style>
    .role_items h4 label {
        float: right;
        font-size: 14px;
    }
    .role_items {
        margin-bottom: 20px;
    }
    .role_items h4 {
        margin-bottom: 25px;
        margin-top: 26px;
        padding: 14px 6px;
        background-color: #6c9399;
        border-radius: 5px;
        font-size: 14;
        color: #fff;
    }
    .choose_area{
        text-align: left;
    }
    .choose_area label{
        float: right;
        color: #fff;
    }
    .fwidth{
        width: 100%;
    }
    .permission_row {
        border: 1px solid #ccc;
        margin-bottom: 30px;
        padding: 10px;
    }
    .permission_row .check_bx{
        margin-left: 10px;
    }
    .check_bx{
        margin-right: 10px;
    }
    .controll_label {
        margin-bottom: 15px;
        width: 100%;
        text-align: revert;
    }
    .role_items {
        padding: 20px;
    }
</style>

<div class="card-body admin-role-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-sm-12">
            <div class="card-footer float-right ml-auto mr-auto">

                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'role_name')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>

            </div>
        </div>
    </div>

    <div class="row role_items">
        <div class="col-sm-12 fwidth">
            <h3 class=" alert alert-danger btn-block choose_area">Choose access area
                <label>Select All <input type="checkbox" value="1" class="select_all" label="Selct All"/></label>
            </h3>
        </div>
        <div class="col-sm-12">

            <?php
            $controller_items = [];
            $controller_list = common\models\AdminRoleList::find()
                    ->where(['status' => '1'])
                    ->all();

            if ($controller_list != NULL) {
                foreach ($controller_list as $controller_li) {

                    if (!in_array($controller_li->controller, $controller_items)) {
                        array_push($controller_items, $controller_li->controller);
                    }
                }
            }
//            print_r($controller_list);
            ?>

            <?php if ($controller_items != NULL) { ?>
                <?php
                foreach ($controller_items as $controller_item) {

                    $name = strtolower(preg_replace('~(?=[A-Z])(?!\A)~', ' ', $controller_item));
                    ?>

                    <?php $get_role_list = \common\models\AdminRoleList::find()->where(['status' => 1, 'controller' => $controller_item])->all(); ?>
                    <?php
                    $not_exist = 0;
                    $count_actual_role_list = count($get_role_list);
                    if ($get_role_list != NULL) {
                        ?>

                        <?php foreach ($get_role_list as $get_role) { ?>

                            <?php
                            if ($model->isNewRecord) { // === false even we insert a new record
                            } else {
                                $check_role_cat_exist = \common\models\AdminRoleLocation::find()->where(['status' => 1, 'role_id' => $model->id, 'role_list_id' => $get_role->id])->one();

                                if ($check_role_cat_exist != NULL) {
                                    $not_exist++;
                                }
                            }
                            ?>

                        <?php } ?>
                        <?php
                    }

                    if ($not_exist != $count_actual_role_list) {
                        $checked_all = '';
                    } else {
                        $checked_all = "checked";
                    }
                    ?>
                    <div class="row permission_row">
                        <div class="col-sm-12">

                            <label  class="controll_label btn btn-primary"><?php echo ucfirst(str_replace("controller", "", $name)); ?> <input type="checkbox" id="adminrolecategory-access_location" <?php echo $checked_all; ?> class="check_bx action_select_all float-right" name="AdminRoleLocation[role_list_id][]" value="0"></label><br/>

                        </div>

                        <?php if ($get_role_list != NULL) { ?>

                            <?php foreach ($get_role_list as $get_role) { ?>

                                <?php
                                if ($model->isNewRecord) { // === false even we insert a new record
                                    $checked = '';
                                } else {
                                    $check_role_cat_exist = \common\models\AdminRoleLocation::find()->where(['status' => 1, 'role_id' => $model->id, 'role_list_id' => $get_role->id])->one();

                                    if ($check_role_cat_exist != NULL) {
                                        $checked = "checked";
                                    } else {
                                        $checked = '';
                                    }
                                }
                                ?>
                                <div class="col-sm-2">

                                    <label class="action_label"><input type="checkbox" id="adminrolecategory-access_location" <?php echo $checked; ?> class="check_bx action_items" name="AdminRoleLocation[role_list_id][]" value="<?php echo $get_role->id; ?>"><?php echo $get_role->action; ?></label><br/>

                                </div>


                                <?php
                            }
                        }
                        ?>
                    </div>
                    <?php
                }
            }
            ?>




        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs(<<< EOT_JS_CODE

        $(document).ready(function(){
         var numberNotCheckedall = $('.action_items:checked').length;
        var totalnumberall = $('.action_items').length;
        if(numberNotCheckedall == totalnumberall){
         $('.select_all').prop( "checked", true );
        }else{
           $('.select_all').prop( "checked", false );

   }
        });
//         var numberNotChecked = $('.action_items:checked').length;
//        var totalnumber = $('.action_items').length;
//        if(numberNotChecked == totalnumber){
//         $('.select_all').prop( "checked", true );
//        }else{
//           $('.select_all').prop( "checked", false );
//
//   }
        $('.select_all').click(function(){
            if($(".select_all").is(':checked')){

                $( ".check_bx" ).each(function( index ) {
                  $(this).prop( "checked", true );
                });

            }else{
                $( ".check_bx" ).each(function( index ) {
                     $(this).prop( "checked", false );
                });

            }

        });
        $('.action_select_all').click(function(){
          var numberNotChecked = $('.action_items:checked').length;
        var totalnumber = $('.action_items').length;
        if(numberNotChecked == totalnumber){
         $('.select_all').prop( "checked", true );
        }else{
           $('.select_all').prop( "checked", false );

   }

            if($(this).is(':checked')){

                $(this).closest('.permission_row').find( ".check_bx" ).each(function( index ) {
                  $(this).prop( "checked", true );
                });

            }else{
         $(this).closest('.permission_row').find( ".check_bx" ).each(function( index ) {
                    $(this).prop( "checked", false );
                });


            }

        });
        $('.action_items').click(function(){
        var numberNotChecked = $(this).closest('.permission_row').find('.action_items:checked').length;
        var totalnumber = $(this).closest('.permission_row').find('.action_items').length;
        if(numberNotChecked == totalnumber){
         $(this).closest('.permission_row').find('.action_select_all').prop( "checked", true );
        }else{
           $(this).closest('.permission_row').find('.action_select_all').prop( "checked", false );

   }

        });

EOT_JS_CODE
);
?>