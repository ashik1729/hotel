<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Modal;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\ProductsServices */
/* @var $form yii\widgets\ActiveForm */
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Banner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body banner-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php
    if (isset($model->id) && $model->id > 0) {
        $model->promotion_from = date('m/d/Y', strtotime($model->promotion_from));
    }
    ?>
    <div class="row">

        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'banner_type')->dropDownList(['0' => 'Free', '1' => 'Paid'], ['prompt' => 'Select Banner Type']) ?>

            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'store')->dropDownList(yii\helpers\ArrayHelper::map(\common\models\Franchise::find()->where(['status' => 10])->all(), 'id', 'first_name'), ['prompt' => 'Choose a Store']) ?>

            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'promotion_id')->dropDownList(yii\helpers\ArrayHelper::map(\common\models\PromotionalCampaign::find()->all(), 'id', 'name'), ['prompt' => 'Choose a Campaign']) ?>

            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable'], ['prompt' => 'Choose Status']) ?>

            </div>
        </div>


        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'promotion_from')->textInput(['type' => 'text', 'class' => 'form-control datepicker']) ?>

            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'sort_order')->textInput(['value' => 0]) ?>

            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'map_type')->dropDownList(['0' => 'No Maping', '1' => 'Products', '2' => 'Category', '3' => 'Merchant', '4' => 'External Link'], ['prompt' => 'Select Item', 'class' => 'form-control map_to_type']) ?>

            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <div class="mapping_to_wrap">
                    <?= $form->field($model, 'map_to')->textInput() ?>

                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'file_type')->dropDownList(['1' => 'Image', '2' => 'File'], ['prompt' => 'Select File']) ?>


            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'description_en')->textarea(['rows' => 2]) ?>


            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'description_ar')->textarea(['rows' => 2]) ?>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <div id="imagePriview">
                    <?php
                    if (isset($model->id) && $model->id > 0 && isset($model->file_and) && $model->file_and !== "") {
                        $imgPath = ((yii\helpers\Url::base())) . '/../uploads/marketing_banners/' . $model->id . '/android/' . $model->file_and;
                    } else {
                        $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                    }

                    echo '<img width="125" style="border: 2px solid #d2d2d2;" src="' . $imgPath . '" />';
                    ?>
                </div>
                <br/>
                <?php
                echo '<label class="control-label">Upload category Image</label>';
                echo FileInput::widget([
                    'model' => $model,
                    'attribute' => 'file_and',
                    'options' => [
//                        'multiple' => true
                        'id' => 'input-2',
                    ],
                    'pluginOptions' => [
                        'showUpload' => false,
                        'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'xlsx'],
                    ]
                ]);
                ?>
                <span class="bmd-help"><?= Html::activeHint($model, 'file_and'); ?></span>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <div id="imagePriview">
                    <?php
                    if (isset($model->id) && $model->id > 0 && isset($model->file_ios) && $model->file_ios !== "") {
                        $imgPath = ((yii\helpers\Url::base())) . '/../uploads/marketing_banners/' . $model->id . '/ios/' . $model->file_ios;
                    } else {
                        $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                    }

                    echo '<img width="125" style="border: 2px solid #d2d2d2;" src="' . $imgPath . '" />';
                    ?>
                </div>
                <br/>
                <?php
                echo '<label class="control-label">Upload category Image</label>';
                echo FileInput::widget([
                    'model' => $model,
                    'attribute' => 'file_ios',
                    'options' => [
//                        'multiple' => true
                        'id' => 'input-3',
                    ],
                    'pluginOptions' => [
                        'showUpload' => false,
                        'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'xlsx'],
                    ]
                ]);
                ?>
                <span class="bmd-help"><?= Html::activeHint($model, 'file'); ?></span>

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


      md.initFormExtendedDatetimepickers();



        var date = new Date();
date.setDate(date.getDate()-1);

//$('.datepicker').datepicker({
//    startDate: date
//});
EOT_JS_CODE
);
?>

<?php
$this->registerJs(<<< EOT_JS_CODE
        $(document).ready(function(){
  var store = $("#banner-store").val();
                var type = $('#banner-map_type').val();
                var cvalue = $('#banner-map_to').val();
        if(type == 1  || type == 2  || type == 3){
                $.ajax({
                   type: "POST",
                   url: baseurl + "/marketing/banner/mapping-to",
                   data: {type: type,store:store}
               }).done(function (data) {
                   var obj = JSON.parse(data);
                   if (obj.status == 200) {
                        $(".field-banner-map_to #banner-map_to").remove();
                        $(".field-banner-map_to").append(obj.message);
        $('#banner-map_to option[value='+cvalue+']').attr('selected','selected');
                   }
               });
        }
            $(document.body).on('change', '.map_to_type', function () {
                var store = $("#banner-store").val();
                var type = $(this).val();
        if(type == 1  || type == 2  || type == 3){
                $.ajax({
                   type: "POST",
                   url: baseurl + "/marketing/banner/mapping-to",
                   data: {type: type,store:store}
               }).done(function (data) {
                   var obj = JSON.parse(data);
                   if (obj.status == 200) {
                        $(".field-banner-map_to #banner-map_to").remove();
                        $(".field-banner-map_to").append(obj.message);
                   }
               });
        }else{
        var html = '<input type="text" id="banner-map_to" class="form-control" name="Banner[map_to]"  aria-invalid="false">';
         $(".field-banner-map_to #banner-map_to").remove();
         $(".field-banner-map_to").append(html);


        }
            });


        });

EOT_JS_CODE
);
?>