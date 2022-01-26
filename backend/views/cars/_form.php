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
                    <a class="nav-link" id="pills-car-option-tab" data-toggle="pill" href="#pills-car-option" role="tab" aria-controls="pills-car-option" aria-selected="false">Car Options</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-car-extra-tab" data-toggle="pill" href="#pills-car-extra" role="tab" aria-controls="pills-car-extra" aria-selected="false">Extras</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-car-docs-tab" data-toggle="pill" href="#pills-car-docs" role="tab" aria-controls="pills-car-docs" aria-selected="false">Documents Requied</a>
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
                    <?php Pjax::begin(['id' => 'info_section']) ?>
                    <?php
                    $get_car_general_infos = \common\models\CarGeneralInformation::find()->all();
                    ?>
                    <div class="attribute_area info_area">
                        <?php if ($get_car_general_infos != NULL) { ?>

                            <div class="info_contents">
                                <?php foreach ($get_car_general_infos as $get_car_general_info) { ?>
                                    <div class="attribute_value_item info_item">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="attr_value_title">
                                                    <h3>
                                                        <span> </span>
                                                        <a class=" delete_info btn btn-xs btn-danger btn-round btn-fab" item_id="<?php echo $get_car_general_info->id; ?>" href="javascript:void(0)">
                                                            <b class="material-icons">delete</b>
                                                            <div class="ripple-container"></div>
                                                        </a>
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="attr_value_contents">
                                                    <div class="attr_value_data">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <?= $form->field($car_general, 'ref_id[]')->dropDownList(ArrayHelper::map(\common\models\GeneralInformationMaster::find()->all(), 'id', 'title'), ['prompt' => 'Choose One Option', 'class' => 'form-control ref_id', 'value' => $get_car_general_info->ref_id]); ?>
                                                            </div>
                                                            <div class="col-sm-6 ">
                                                                <?= $form->field($car_general, 'value[]')->textInput(['lang' => 1, 'class' => 'form-control mt-4  info_val', 'value' => $get_car_general_info->value])->label('Value'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="info_contents">
                                <div class="attribute_value_item info_item">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="attr_value_title">
                                                <h3>
                                                    <span> </span>
                                                    <a class=" delete_info btn btn-xs btn-danger btn-round btn-fab" item_id="0" href="javascript:void(0)">
                                                        <b class="material-icons">delete</b>
                                                        <div class="ripple-container"></div>
                                                    </a>
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="attr_value_contents">
                                                <div class="attr_value_data">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <?= $form->field($car_general, 'ref_id[]')->dropDownList(ArrayHelper::map(\common\models\GeneralInformationMaster::find()->all(), 'id', 'title'), ['prompt' => 'Choose One Option', 'class' => 'form-control ref_id']); ?>
                                                        </div>
                                                        <div class="col-sm-6 ">
                                                            <?= $form->field($car_general, 'value[]')->textInput(['lang' => 1, 'class' => 'form-control mt-4  info_val'])->label('Value'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="attr_contents_btn_wrapper">
                            <button class="btn  add_info" tabindex="0" type="button"><b class="material-icons">add</b> Add New Information</button>
                        </div>
                    </div>
                    <?php Pjax::end()
                    ?>

                </div>


                <!--Car Options-->


                <div class="tab-pane fade   box_item" id="pills-car-option" role="tabpanel" aria-labelledby="pills-car-option-tab">
                    <?php Pjax::begin(['id' => 'car_option_section']) ?>
                    <?php
                    $get_car_options = \common\models\CarOptions::find()->all();
                    ?>
                    <div class="attribute_area car_option_area">
                        <?php if ($get_car_options != NULL) { ?>

                            <div class="car_option_contents">
                                <?php foreach ($get_car_options as $get_car_option) { ?>
                                    <div class="attribute_value_item car_option_item">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="attr_value_title">
                                                    <h3>
                                                        <span> </span>
                                                        <a class=" delete_car_option btn btn-xs btn-danger btn-round btn-fab" item_id="<?php echo $get_car_option->id; ?>" href="javascript:void(0)">
                                                            <b class="material-icons">delete</b>
                                                            <div class="ripple-container"></div>
                                                        </a>
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="attr_value_contents">
                                                    <div class="attr_value_data">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <?= $form->field($car_option, 'ref_id[]')->dropDownList(ArrayHelper::map(\common\models\CarOptionMaster::find()->all(), 'id', 'title'), ['prompt' => 'Choose One Option', 'class' => 'form-control ref_id', 'value' => $get_car_option->ref_id]); ?>
                                                            </div>
                                                            <div class="col-sm-6 ">
                                                                <?= $form->field($car_option, 'value[]')->textInput(['lang' => 1, 'class' => 'form-control mt-4  info_val', 'value' => $get_car_option->value])->label('Value'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="car_option_contents">

                                <div class="attribute_value_item car_option_item">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="attr_value_title">
                                                <h3>
                                                    <span> </span>
                                                    <a class=" delete_car_option btn btn-xs btn-danger btn-round btn-fab" item_id="0" href="javascript:void(0)">
                                                        <b class="material-icons">delete</b>
                                                        <div class="ripple-container"></div>
                                                    </a>
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="attr_value_contents">
                                                <div class="attr_value_data">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <?= $form->field($car_option, 'ref_id[]')->dropDownList(ArrayHelper::map(\common\models\CarOptionMaster::find()->all(), 'id', 'title'), ['prompt' => 'Choose One Option', 'class' => 'form-control ref_id']); ?>
                                                        </div>
                                                        <div class="col-sm-6 ">
                                                            <?= $form->field($car_option, 'value[]')->textInput(['lang' => 1, 'class' => 'form-control mt-4  info_val'])->label('Value'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php } ?>
                        <div class="attr_contents_btn_wrapper">
                            <button class="btn  add_car_option" tabindex="0" type="button"><b class="material-icons">add</b> Add New Car Option</button>
                        </div>
                    </div>
                    <?php Pjax::end()
                    ?>

                </div>
                <div class="tab-pane fade   box_item" id="pills-car-extra" role="tabpanel" aria-labelledby="pills-car-extra-tab">
                    <?php Pjax::begin(['id' => 'car_extra_section']) ?>
                    <?php
                    $get_car_extras = \common\models\CarExtras::find()->all();
                    ?>
                    <div class="attribute_area car_extra_area">
                        <?php if ($get_car_extras != NULL) { ?>
                            <div class="car_extra_contents">
                                <?php foreach ($get_car_extras as $get_car_extra) { ?>
                                    <div class="attribute_value_item car_extra_item">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="attr_value_title">
                                                    <h3>
                                                        <span> </span>
                                                        <a class=" delete_car_extra btn btn-xs btn-danger btn-round btn-fab" item_id="<?php echo $get_car_extra->id; ?>" href="javascript:void(0)">
                                                            <b class="material-icons">delete</b>
                                                            <div class="ripple-container"></div>
                                                        </a>
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="attr_value_contents">
                                                    <div class="attr_value_data">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <?= $form->field($car_option, 'ref_id[]')->dropDownList(ArrayHelper::map(\common\models\ExtrasMaster::find()->all(), 'id', 'title'), ['prompt' => 'Choose One Option', 'class' => 'form-control ref_id', 'value' => $get_car_extra->ref_id]); ?>
                                                            </div>
                                                            <div class="col-sm-6 ">
                                                                <?= $form->field($car_option, 'value[]')->textInput(['lang' => 1, 'class' => 'form-control mt-4  info_val', 'value' => $get_car_extra->value])->label('Value'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="car_option_contents">

                                <div class="attribute_value_item car_extra_item">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="attr_value_title">
                                                <h3>
                                                    <span> </span>
                                                    <a class=" delete_car_extra btn btn-xs btn-danger btn-round btn-fab" item_id="0" href="javascript:void(0)">
                                                        <b class="material-icons">delete</b>
                                                        <div class="ripple-container"></div>
                                                    </a>
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="attr_value_contents">
                                                <div class="attr_value_data">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <?= $form->field($car_option, 'ref_id[]')->dropDownList(ArrayHelper::map(\common\models\ExtrasMaster::find()->all(), 'id', 'title'), ['prompt' => 'Choose One Option', 'class' => 'form-control ref_id']); ?>
                                                        </div>
                                                        <div class="col-sm-6 ">
                                                            <?= $form->field($car_option, 'value[]')->textInput(['lang' => 1, 'class' => 'form-control mt-4  info_val'])->label('Value'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php } ?>
                        <div class="attr_contents_btn_wrapper">
                            <button class="btn  add_car_extra" tabindex="0" type="button"><b class="material-icons">add</b> Add New Car Extra</button>
                        </div>
                    </div>
                    <?php Pjax::end()
                    ?>

                </div>
                <div class="tab-pane fade   box_item" id="pills-car-docs" role="tabpanel" aria-labelledby="pills-car-docs-tab">
                    <?php Pjax::begin(['id' => 'car_docs_section']) ?>
                    <?php
                    $get_car_docs = \common\models\CarDocuments::find()->all();
                    ?>
                    <div class="attribute_area car_docs_area">
                        <?php if ($get_car_docs != NULL) { ?>
                            <div class="car_docs_contents">
                                <?php foreach ($get_car_docs as $get_car_doc) { ?>
                                    <div class="attribute_value_item car_docs_item">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="attr_value_title">
                                                    <h3>
                                                        <span> </span>
                                                        <a class=" delete_car_docs btn btn-xs btn-danger btn-round btn-fab" item_id="<?php echo $get_car_doc->id; ?>" href="javascript:void(0)">
                                                            <b class="material-icons">delete</b>
                                                            <div class="ripple-container"></div>
                                                        </a>
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="attr_value_contents">
                                                    <div class="attr_value_data">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <?= $form->field($car_option, 'ref_id[]')->dropDownList(ArrayHelper::map(\common\models\DocumentsMaster::find()->all(), 'id', 'title'), ['prompt' => 'Choose One Option', 'class' => 'form-control ref_id', 'value' => $get_car_doc->ref_id]); ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } else {  ?>
                            <div class="car_option_contents">
                                <div class="attribute_value_item car_docs_item">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="attr_value_title">
                                                <h3>
                                                    <span> </span>
                                                    <a class=" delete_car_docs btn btn-xs btn-danger btn-round btn-fab" item_id="0" href="javascript:void(0)">
                                                        <b class="material-icons">delete</b>
                                                        <div class="ripple-container"></div>
                                                    </a>
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="attr_value_contents">
                                                <div class="attr_value_data">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <?= $form->field($car_option, 'ref_id[]')->dropDownList(ArrayHelper::map(\common\models\DocumentsMaster::find()->all(), 'id', 'title'), ['prompt' => 'Choose One Option', 'class' => 'form-control ref_id']); ?>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="attr_contents_btn_wrapper">
                            <button class="btn  add_car_docs" tabindex="0" type="button"><b class="material-icons">add</b> Add New Car Documents</button>
                        </div>
                    </div>
                    <?php Pjax::end()
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="tempHtml">

    </div>
    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>

</script>
<?php
$this->registerJs(
    <<< EOT_JS_CODE


$(document).ready(function(){
var general_data_html = $('.info_item:first-child').html();
var general_data = "<div class='attribute_value_item info_item'>" + general_data_html + "</div>";
var car_option_data_html = $('.car_option_item:first-child').html();
var car_option_general_data = "<div class='attribute_value_item car_option_item'>" + car_option_data_html + "</div>";
var car_extra_data_html = $('.car_extra_item:first-child').html();
var car_extra_general_data = "<div class='attribute_value_item car_extra_item'>" + car_extra_data_html + "</div>";

    $(document.body).on("click", ".add_info", function (e) {
            $(this).closest('.info_area').find(".info_contents").append(general_data);
            $('.info_item:last').find('.ref_id').val("");//will give third li
            $('.info_item:last').find('.info_val').val("");//will give third li
            $('.info_item:last').find('.delete_info').attr("item_id",0);//will give third li

        });
        // -----------------------------------  Delete Info------------------------------
        $(document).on("click", ".delete_info", function () {
            $(".loader-wrapp").show();
            if (confirm('Are You Sure Want Delet this value')) {
                var item_id = $(this).attr('item_id');
                if (item_id != 0) {
                    $.ajax({
                        url: basepath + "/cars/delete-info",
                        type: "POST",
                        data: {item_id: item_id},
                        success: function (data)
                        {
                            var obj = JSON.parse(data);
                            if (obj.status == 200) {
                                $.pjax.reload('#info_section', {timeout: false, async: true});
                                $(".loader-wrapp").hide();
                                return true;
                            } else {
                                $('.attr_error').html(obj.message);
                            }
                            $(".loader-wrapp").hide();
                        },
                        error: function (e) {
                            console.log(e);
                            $(".loader-wrapp").hide();
                        }
                    });
                } else {
                    $(this).closest(".info_item").remove();
                    $(".loader-wrapp").hide();
    
                }
            }
        });
        //------------------------- Car Option ---------------------------
        $(document.body).on("click", ".add_car_option", function (e) {
            $(this).closest('.car_option_area').find(".car_option_contents").append(car_option_general_data);
            $('.car_option_item:last').find('.ref_id').val("");//will give third li
            $('.car_option_item:last').find('.info_val').val("");//will give third li
            $('.car_option_item:last').find('.delete_car_option').attr("item_id",0);//will give third li
        });
        // -----------------------------------  Delete Info------------------------------
        $(document).on("click", ".delete_car_option", function () {
            $(".loader-wrapp").show();
            if (confirm('Are You Sure Want Delet this value')) {
                var item_id = $(this).attr('item_id');
                if (item_id != 0) {
                    $.ajax({
                        url: basepath + "/cars/delete-car-option",
                        type: "POST",
                        data: {item_id: item_id},
                        success: function (data)
                        {
                            var obj = JSON.parse(data);
                            if (obj.status == 200) {
                                $.pjax.reload('#car_option_section', {timeout: false, async: true});
                                $(".loader-wrapp").hide();
                                return true;
                            } else {
                                $('.attr_error').html(obj.message);
                            }
                            $(".loader-wrapp").hide();
                        },
                        error: function (e) {
                            console.log(e);
                            $(".loader-wrapp").hide();
                        }
                    });
                } else {
                    $(this).closest(".car_option_item").remove();
                    $(".loader-wrapp").hide();
                }
            }
        });
        

        //------------------------- Car Extra ---------------------------
        $(document.body).on("click", ".add_car_extra", function (e) {
            $(this).closest('.car_extra_area').find(".car_extra_contents").append(car_extra_general_data);
            $('.car_extra_item:last').find('.ref_id').val("");//will give third li
            $('.car_extra_item:last').find('.info_val').val("");//will give third li
            $('.car_extra_item:last').find('.delete_car_extra').attr("item_id",0);//will give third li
        });
        // -----------------------------------  Delete Info------------------------------
        $(document).on("click", ".delete_car_extra", function () {
            $(".loader-wrapp").show();
            if (confirm('Are You Sure Want Delet this value')) {
                var item_id = $(this).attr('item_id');
                if (item_id != 0) {
                    $.ajax({
                        url: basepath + "/cars/delete-car-extra",
                        type: "POST",
                        data: {item_id: item_id},
                        success: function (data)
                        {
                            var obj = JSON.parse(data);
                            if (obj.status == 200) {
                                $.pjax.reload('#car_extra_section', {timeout: false, async: true});
                                $(".loader-wrapp").hide();
                                return true;
                            } else {
                                $('.attr_error').html(obj.message);
                            }
                            $(".loader-wrapp").hide();
                        },
                        error: function (e) {
                            console.log(e);
                            $(".loader-wrapp").hide();
                        }
                    });
                } else {
                    $(this).closest(".car_extra_item").remove();
                    $(".loader-wrapp").hide();
                }
            }
        });
        
}); 
EOT_JS_CODE
);
?>