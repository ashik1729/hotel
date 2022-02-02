<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PackagesDate */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
    $packages = \common\models\ProductsServices::find()->where(['status' => 1])->all();
    $options = array();
    if ($packages != NULL) {
        foreach ($packages as $package) {
            if (!empty($package)) {
                $options[$package->id] = $package->package_title;
            }
        }
    }
    ?>

<div class="card-body packages-date-form">
    <?php $form = ActiveForm::begin(['action' => 'save-package-date-price','options' => ['class' => 'form_pkg_content']]); ?>
   
        <div class="col-sm-12">
            <div class="row">
                <?php if(!isset($package_id) ||  $package_id <= 0) {?>
                    <div class="col-sm-3">

                        <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'package_id')->dropDownList($options, ['prompt' => 'Select Package']);
                            ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-2">

                    <div class="form-group bmd-form-group">
                        <?= $form->field($model, 'package_date')->textInput(['type' => 'date', 'id' => 'calenders', 'class' => 'form-control']) ?>
                    </div>
                </div>
                <div class="col-sm-2">

                    <div class="form-group bmd-form-group">
                    
                    <?= $form->field($model, 'package_quantity')->textInput([]) ?>
                    </div>
                </div>
            </div>

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
                                <i class="fa fa-plus add_pkg btn_add_pkg_details btn btn-fab"></i>
                               
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
                                
            </div>
        </div>
       
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
    $(document.body).on("click",".remove_pkg",function(e){
        $(this).closest('.added-section').remove();
    });
    $(document.body).on("click",".remove_pkg",function(e){
        $(this).closest('.added-section').remove();
    });

    
EOT_JS_CODE
);
?>
