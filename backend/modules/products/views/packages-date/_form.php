<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PackagesDate */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="card-body packages-date-form">
    <?php $form = ActiveForm::begin(['action' => 'save-package-date-price','options' => ['class' => 'form_pkg_content']]); ?>
    <?php if($model->isNewRecord) { 
        $disbaled = false;
    }  else {
        $disbaled = true;
    }
    ?>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-2">

                    <div class="form-group bmd-form-group">
                        <?= $form->field($model, 'package_date')->textInput(['type' => 'date', 'id' => 'calenders', 'class' => 'form-control','disabled' => $disbaled]) ?>
                    </div>
                </div>
                <div class="col-sm-2">

                    <div class="form-group bmd-form-group">
                    
                    <?= $form->field($model, 'package_quantity')->textInput([]) ?>
                    </div>
                </div>
            </div>

        </div>
        <?php if($model->isNewRecord) {?>
            <div class="row price_repeat_sctn">  
                <div class="col-sm-12">
                    <div class="attr_value_contents">
                        <div class="attr_value_data">
                            <div class="row"> 
                                <div class="col-sm-3 mt-3">
                                    <div class="attr_price">
                                        <?= $form->field($pkg_price, 'min_person[]')->textInput(['class' => 'pkg_min form-control']) ?>
                                    </div>
                                </div>
                                <div class="col-sm-3 mt-3">
                                        <?= $form->field($pkg_price, 'max_person[]')->textInput(['class' => 'pkg_max form-control']) ?>
                                </div>
                                <div class="col-sm-3 mt-3">
                                    <?= $form->field($pkg_price, 'price[]')->textInput(['class' => 'pkg_price form-control']) ?>
                                </div>
                            
                                    <div class="col-sm-2 repeat-action">
                                        <i class="fa fa-plus add_pkg btn_add_pkg_details btn btn-fab"></i>
                                    
                                    </div>
                            </div>
                        </div>
                        
                    </div>
                                    
                </div>
            </div>
           
        <?php } else {?>
            <div class="row price_repeat_sctn">  
                <div class="col-sm-12">
                    <div class="attr_value_contents">
                        <div class="attr_value_data">
                            <div class="row"> 
                                <div class="col-sm-3 mt-3">
                                    <div class="attr_price">
                                        <?= $form->field($pkg_price, 'min_person')->textInput(['class' => 'pkg_min form-control']) ?>
                                    </div>
                                </div>
                                <div class="col-sm-3 mt-3">
                                        <?= $form->field($pkg_price, 'max_person')->textInput(['class' => 'pkg_max form-control']) ?>
                                </div>
                                <div class="col-sm-3 mt-3">
                                    <?= $form->field($pkg_price, 'price')->textInput(['class' => 'pkg_price form-control']) ?>
                                </div>
                            
                              
                            </div>
                        </div>
                        
                    </div>
                                    
                </div>
            </div>
            <?= $form->field($model, 'id')->hiddenInput([])->label(false)  // package_date table id?>
            <?= $form->field($pkg_price, 'id')->hiddenInput([])->label(false)  // package_price table id?>
        <?php }?>
        
        <?= $form->field($model, 'package_id')->hiddenInput(['value' => $pkg_id])->label(false) ?>
        <div class="modal-footer">
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
                $(this).find('.repeat-action').append('<i class="fa fa-remove remove_pkg btn btn-fab"></i>')
            }
        });
        
      
    });   
    
    $(document.body).on("click",".save-package-details",function(e){ 
        e.preventDefault();
        var error = 0;
       
        $( ".pkg_min" ).each(function( index ) {
            if($(this).val() == "") {
                $(this).closest('.field-packagesprice-min_person').find('.help-block').text("Min Person  cannot be blank");
                error++;
            }
        });
        $( ".pkg_max" ).each(function( index ) {
            if($(this).val() == "") {
                $(this).closest('.field-packagesprice-max_person').find('.help-block').text("Max Person  cannot be blank");
                error++;
            }
        });
        $( ".pkg_price" ).each(function( index ) {
            if($(this).val() == "") {
                $(this).closest('.field-packagesprice-price').find('.help-block').text("Price cannot be blank");
                error++;
            }
        });
        if(error == 0) {
            $('.save-package-details').submit();
        }


    });
    
    
    $(document.body).on("click",".remove_pkg",function(e){
        $(this).closest('.added-section').remove();
    });
    $(document.body).on("click",".remove_pkg",function(e){
        $(this).closest('.added-section').remove();
    });
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd;
    $('#calenders').attr('min',today);

    
EOT_JS_CODE
);
?>
