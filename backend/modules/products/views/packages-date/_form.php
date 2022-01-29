<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PackagesDate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body packages-date-form">

    <?php /* $form = ActiveForm::begin(); ?>
    <div class="row">

        
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'package_id')->textInput() ?>

                    </div>
                </div>
                
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'package_date')->textInput() ?>

                    </div>
                </div>
                
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'package_quantity')->textInput() ?>

                    </div>
                </div>
                    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); */ ?>
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form_pkg_content']]); ?>
    <div class="row">
        <div class="col-sm-2">

            <div class="form-group bmd-form-group">
            <?= $form->field($model, 'package_date')->textInput(['type' => 'date', 'id' => 'calenders', 'class' => 'form-control']) ?>
                <?php // $form->field($model, 'store')->dropDownList($franchise, ['prompt' => 'Choose A Franchise', 'class' => 'form-control franchise']); ?>
            </div>
        </div>
        <div class="col-sm-2">

            <div class="form-group bmd-form-group">
               
            <?= $form->field($model, 'package_quantity')->textInput([]) ?>
            </div>
        </div>
        <!-- <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?php // $form->field($usermodel, 'first_name')->textInput(['class' => 'form-control first_name user_info']) ?>

            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?php // $form->field($usermodel, 'last_name')->textInput(['class' => 'form-control last_name user_info']) ?>

            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?php // $form->field($usermodel, 'email')->textInput(['class' => 'form-control email user_info']) ?>

            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?php // $form->field($usermodel, 'mobile_number')->textInput(['class' => 'form-control mobile_number user_info']) ?>

            </div>
        </div> -->

        </div>
        <div class="row price_repeat_sctn">  
            <div class="col-sm-12">
                <div class="attr_value_contents">
               
                    <div class="attr_value_data">
                        <div class="row"> 
                            <div class="col-sm-3 mt-3">
                                <div class="attr_price">
                                    <?= $form->field($pkg_price, 'min_person[]')->textInput([]) ?>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-3">
                                    <?= $form->field($pkg_price, 'max_person[]')->textInput([]) ?>
                            </div>
                            <div class="col-sm-3 mt-3">
                                <?= $form->field($pkg_price, 'price[]')->Input(['value' => 0]) ?>
                            </div>
                            <div class="col-sm-2 repeat-action">
                                <i class="fa fa-plus add_pkg btn_add_pkg_details"></i>
                               
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
                                
            </div>
        </div>

       

    
        <?php
        // Pjax::begin(['id' => 'order_section']);
        $carts = [];
        if (Yii::$app->session->get('cart_session')) {
            // $carts = common\models\Cart::find()->where(['session_id' => Yii::$app->session->get('cart_session')])->all();
        }
        ?>
        <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="Submit" class="btn btn-primary save-package-details">Save </button>
                </div>

    
    <?php ActiveForm::end(); ?>
</div>

<?php
    $this->registerJs(<<< EOT_JS_CODE
    
    $(document.body).on("click",".btn_add_pkg_details",function(e){
        
        var html_content = $(this).closest('.price_repeat_sctn').find('div').first().html();
        console.log(html_content);
        html_content = '<div class="col-sm-12 added-section">'+html_content+'</div>';
        $('.price_repeat_sctn').append(html_content);
        $('.added-section').each(function() {
            if(!$(this).find('.repeat-action i').hasClass('remove_pkg')) {
                $(this).find('.repeat-action').append('<i class="fa fa-remove remove_pkg"></i>')
            }
        });
        
      
    });    
    $(document.body).on("click",".remove_pkg",function(e){
        $(this).closest('.added-section').remove();
    });

    /*    $(document.body).on('click', '.save-pckg-details', function (e) {

            $('.loader-wrapp').show();
            var form = $(".form_pkg_content");
            e.preventDefault();
            if (form.find('.has-error').length)
            {
                return false;
            }
            var formData = form.serialize();
            $.ajax({
                url: form.attr("action"),
                type: "POST",
                data: formData,
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj.status == 200) {
                        $('#package_content').modal('hide');
                        $('.loader-wrapp').hide();
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    } else {
                        console.log(obj.error);
                                              $('.loader-wrapp').hide();
                                              $('.errmsg').text(obj.error);

                    }
                },
                error: function () {

                    alert("Something went wrong");
                    $('.loader-wrapp').hide();

                }

            });

            }).on('submit', function (e) {

            e.preventDefault();

        });
        
        $(document.body).on("click", ".btn_add_pkg_details", function (e) {
         //   alert();
            var result_attr_value_html = $(".attr_value_contents").html();
            $('.temp_div').append(result_attr_value_html)
            
            console.log(result_attr_value_html);
            $(this).closest('.attr_value_contents').append(result_attr_value_html);
            // var count = $(this).closest('.attribute_item').attr('key');
            // $('[key=' + count + '] input').each(function (key, value) {
            //     var name = $(this).attr('name');
            //     var newname = name.replace("attcnt", count);
            //     $(this).attr('name', newname);
            // });
        }); */

EOT_JS_CODE
);
?>
