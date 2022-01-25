<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Cars */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body cars-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-sm-12 col-lg-2">
            <ul class="nav nav-pills mb-3 pdt_ul" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-general-tab" data-toggle="pill" href="#pills-general" role="tab" aria-controls="pills-general" aria-selected="true">Content</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="pills-general-information-tab" data-toggle="pill" href="#pills-general-information" role="tab" aria-controls="pills-general-information" aria-selected="false">General Information</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-attributes-tab" data-toggle="pill" href="#pills-attributes" role="tab" aria-controls="pills-attributes" aria-selected="false">Car Options</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-seo-tab" data-toggle="pill" href="#pills-seo" role="tab" aria-controls="pills-seo" aria-selected="false">Extras</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-seo-tab" data-toggle="pill" href="#pills-seo" role="tab" aria-controls="pills-seo" aria-selected="false">Documents Requied</a>
                </li>
            </ul>
        </div>
        <div class="col-sm-12 col-lg-10">

            <div class="clearfix">
                <?= $form->errorSummary($model); ?>
                <?php // $form->errorSummary($car_general); 
                ?>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <!--<div class="tab-pane fade show active" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab">-->
                <div class="tab-pane fade  show active box_item" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'title')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group bmd-form-group">
                                <label class="control-label" for="productsservices-merchant_id">Choose Brand
                                </label>
                                <?php Pjax::begin(['id' => 'product_service']) ?>

                                <?php
                                echo Select2::widget([
                                    'model' => $model,
                                    'attribute' => 'brand',
                                    'data' => ArrayHelper::map(\common\models\Brands::find()->where(['status' => 1])->all(), 'id', 'title'),
                                    'theme' => Select2::THEME_MATERIAL,
                                    'options' => ['placeholder' => 'Select a  Brand.', 'class' => 'change_category'],
                                    'pluginOptions' => [
                                        // 'tags' => true,
                                        'closeOnSelect' => true,
                                        'allowClear' => true,
                                        'maximumInputLength' => 40
                                    ],
                                ]);
                                ?>

                                <?php Pjax::end() ?>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group bmd-form-group">
                                <label class="control-label" for="productsservices-merchant_id">Choose Brand
                                </label>
                                <?php Pjax::begin(['id' => 'product_service']) ?>

                                <?php
                                echo Select2::widget([
                                    'model' => $model,
                                    'attribute' => 'type_of_car',
                                    'data' => ArrayHelper::map(\common\models\TypeOfCar::find()->where(['status' => 1])->all(), 'id', 'title'),
                                    'theme' => Select2::THEME_MATERIAL,
                                    'options' => ['placeholder' => 'Select a  Type Of Car.', 'class' => 'change_category'],
                                    'pluginOptions' => [
                                        // 'tags' => true,
                                        'closeOnSelect' => true,
                                        'allowClear' => true,
                                        'maximumInputLength' => 40
                                    ],
                                ]);
                                ?>

                                <?php Pjax::end() ?>

                            </div>
                        </div>


                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'long_description')->textarea(['rows' => 6]) ?>

                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'short_description')->textarea(['rows' => 6]) ?>

                            </div>
                        </div>



                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'model_year')->textInput(['maxlength' => true, 'type' => 'number']) ?>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'series')->textInput(['maxlength' => true]) ?>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'day_price')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'day_offer')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'week_price')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'week_offer')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'month_price')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'month_offer')->textInput() ?>

                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
                                <div id="imagePriview">
                                    <?php
                                    if (isset($model->id) && $model->id > 0 && isset($model->image) && $model->image !== "") {
                                        $imgPath = ((yii\helpers\Url::base())) . '/../uploads/cars/' . $model->id . '/image/' . $model->image;
                                    } else {
                                        $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                                    }
                                    echo '<img width="125" style="border: 2px solid #d2d2d2;" src="' . $imgPath . '" />';
                                    ?>
                                </div>
                                <br />
                                <?php
                                echo '<label class="control-label">Upload category Image</label>';
                                echo FileInput::widget([
                                    'model' => $model,
                                    'attribute' => 'image',
                                    'options' => [
                                        //                        'multiple' => true
                                        'id' => 'input-2',
                                    ],
                                    'pluginOptions' => [
                                        'showUpload' => false,
                                        'allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
                                    ]
                                ]);
                                ?>
                                <span class="bmd-help"><?= Html::activeHint($model, 'image'); ?></span>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
                                <div id="imagePriview">
                                    <?php
                                    $images = explode(',', $model->gallery);
                                    $result_html = '';
                                    if ($images != NULL) {
                                        foreach ($images as $image) {
                                            if (isset($model->id) && $model->id > 0 && isset($model->gallery) && $model->gallery !== "") {
                                                $imgPath = ((yii\helpers\Url::base())) . '/../uploads/cars/' . $model->id . '/gallery/' . $image;
                                            } else {
                                                $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                                            }
                                            $delete_url = Yii::$app->request->baseUrl . '/cars/gallery-delete?id=' . $model->id . '&item=' . $image;
                                            $result_html .= '<div class ="img_gallery"><a href="' . $delete_url . '"><img width="125" style="border: 2px solid #d2d2d2;" src="' . $imgPath . '" /><i class="fa fa-trash trash_file"></i></a></div>';
                                        }
                                        echo $result_html;
                                    }
                                    ?>
                                    <div class="clearfix"></div>
                                </div>
                                <br />
                                <?php
                                echo '<label class="control-label">Upload  Gallery (If any)</label>';
                                echo FileInput::widget([
                                    'model' => $model,
                                    'attribute' => 'gallery',
                                    'options' => [
                                        'id' => 'input-3',
                                        'multiple' => true
                                    ],
                                    'pluginOptions' => [
                                        'showUpload' => false,
                                        'allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
                                    ]
                                ]);
                                ?>
                                <span class="bmd-help"><?= Html::activeHint($model, 'gallery'); ?></span>

                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'sort_order')->textInput() ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade   box_item" id="pills-general-information" role="tabpanel" aria-labelledby="pills-general-information-tab">
                    <?php Pjax::begin(['id' => 'attribute_section']) ?>
                    <?php
                    $get_car_general_infos = \common\models\CarGeneralInformation::find()->all();
                    ?>
                    <div class="attribute_area">

                        <?php if ($get_product_attributes != NULL) { ?>
                            <?php
                            $m = 0;

                            foreach ($get_product_attributes as $get_product_attribute) {
                                $attr_list_data = \common\models\Attributes::findOne(['id' => $get_product_attribute->attributes_id]);
                                $pStatus = "";
                                if ($attr_list_data != NULL) {
                                    $attr_list_value_datas = \common\models\ProductAttributesValue::find()->where(['product_id' => $model->id, 'product_attributes_id' => $get_product_attribute->id])->all();
                                    if ($get_product_attribute->price_status == 1) {
                                        $pStatus = "checked";
                                    }
                            ?>
                                    <div key="<?= $m; ?>" class="attribute_item">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="attr_title">
                                                    <h3>
                                                        <span><?= $attr_list_data->name; ?></span>
                                                        <a class="delete_attr_item btn btn-xs btn-danger btn-round btn-fab" get_product_attribute="<?= $get_product_attribute->id; ?>" style="display:none" href="javascript:void(0)">
                                                            <b class="material-icons">delete</b>
                                                            <div class="ripple-container"></div>
                                                        </a>
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="attr_contents">
                                                    <div class="attr_data">
                                                        <div class="row">
                                                            <div class="col-sm-4 attr_data_name">
                                                                <h5 class="attr_head">Attribute Name
                                                                </h5>
                                                            </div>
                                                            <div class="col-sm-4 attr_parent">
                                                            <?= $form->field($car_general, 'car_id')->hiddenInput(['lang' => 1, 'value' => $attr_list_data->name, 'class' => 'form-control mt-4 change_attr_name attr_en'])->label('English'); ?>
                                                                <?= $form->field($car_general, 'car_id[]')->textInput(['lang' => 1, 'value' => $attr_list_data->name, 'class' => 'form-control mt-4 change_attr_name attr_en'])->label('English'); ?>
                                                                <div class="pop_over_content ">
                                                                    <div class="loader_wrapper ">
                                                                        <div class="loader">
                                                                        </div>
                                                                        <h4 class="text-center mt-2"> Loading suggestion...</h4>
                                                                    </div>

                                                                    <ul class="list-unstyled " role="listbox">

                                                                    </ul>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-4 attr_data_name">
                                                                <h5 class="attr_head">Attribute Name
                                                                </h5>
                                                            </div>
                                                            <div class="col-sm-4 attr_parent">
                                                                <?= $form->field($car_general, 'car_id[]')->textInput(['lang' => 1, 'value' => $attr_list_data->name, 'class' => 'form-control mt-4 change_attr_name attr_en'])->label('English'); ?>

                                                                
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="attr_contents_btn_wrapper">
                                                    <button class="btn btn_add_attr_value" tabindex="0" type="button"><b class="material-icons">add</b>Add New Information</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php
                                $m++;
                            }
                            ?>
                        <?php } else { ?>
                            <div key="0" class="attribute_item">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="attr_title">
                                            <h3>
                                                <span><?= $attr_list_data->name; ?></span>
                                                <a class="delete_attr_item btn btn-xs btn-danger btn-round btn-fab" get_product_attribute="<?= $get_product_attribute->id; ?>" style="display:none" href="javascript:void(0)">
                                                    <b class="material-icons">delete</b>
                                                    <div class="ripple-container"></div>
                                                </a>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="attr_contents">
                                            <div class="attr_data">
                                                <div class="row">
                                                    <div class="col-sm-4 attr_data_name">
                                                        <h5 class="attr_head">Attribute Name
                                                        </h5>
                                                    </div>
                                                    <div class="col-sm-4 attr_parent">
                                                        <?= $form->field($car_general, 'car_id[]')->textInput(['lang' => 1, 'value' => $attr_list_data->name, 'class' => 'form-control mt-4 change_attr_name attr_en'])->label('English'); ?>

                                                        <div class="pop_over_content ">
                                                            <div class="loader_wrapper ">
                                                                <div class="loader">
                                                                </div>
                                                                <h4 class="text-center mt-2"> Loading suggestion...</h4>
                                                            </div>

                                                            <ul class="list-unstyled " role="listbox">

                                                            </ul>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4 attr_data_name">
                                                        <h5 class="attr_head">Attribute Name
                                                        </h5>
                                                    </div>
                                                    <div class="col-sm-4 attr_parent">
                                                        <?= $form->field($car_general, 'car_id[]')->textInput(['lang' => 1, 'value' => $attr_list_data->name, 'class' => 'form-control mt-4 change_attr_name attr_en'])->label('English'); ?>

                                                        <div class="pop_over_content ">
                                                            <div class="loader_wrapper ">
                                                                <div class="loader">
                                                                </div>
                                                                <h4 class="text-center mt-2"> Loading suggestion...</h4>
                                                            </div>

                                                            <ul class="list-unstyled " role="listbox">

                                                            </ul>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="attr_contents_btn_wrapper">
                                            <button class="btn btn_add_attr_value" tabindex="0" type="button"><b class="material-icons">add</b> Add New Information</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php Pjax::end()
                    ?>

                </div>
            </div>
        </div>


    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs(
    <<< EOT_JS_CODE




$(document.body).on("click", ".btn_add_attr_value", function (e) {
        var result_attr_value_html = "<div class='attribute_value_item'>" + attr_value_html + "</div>";
        $(this).closest('.attribute_item').find(".attr_contents").append(result_attr_value_html);
        var count = $(this).closest('.attribute_item').attr('key');
        $('[key=' + count + '] input').each(function (key, value) {
            var name = $(this).attr('name');
            var newname = name.replace("attcnt", count);
            $(this).attr('name', newname);
        });
    });
    
EOT_JS_CODE
);
?>