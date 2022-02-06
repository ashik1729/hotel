<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Modal;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

use dosamigos\ckeditor\CKEditor;
/* @var $this yii\web\View */
/* @var $model common\models\ProductsServices */
/* @var $form yii\widgets\ActiveForm */
use yii\widgets\Pjax;
?>

<?php
$formatJs = <<< 'JS'
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
    }
    var markup =
'<div class="row">' +
    '<div class="col-sm-12 choose_attr" name_en="'+repo.text_en+'" name_ar="'+repo.text_ar+'">' + repo.text + '</div>' +
'</div>';
    return '<div style="overflow:hidden;">' + markup + '</div>';
};
var formatRepoSelection = function (repo) {
    return repo.text;
}
JS;

// Register the formatting script
$this->registerJs($formatJs, yii\web\View::POS_HEAD);

// script to parse the results into the format expected by Select2
$resultsJs = <<< JS
function (data, params) {
    params.page = params.page || 1;
    return {
        results: data.items,
        pagination: {
            more: (params.page * 30) < data.total_count
        }
    };
}
JS;
?>
<!-- Tabs content -->
<div class="card-body products-services-form">

    <?php if (Yii::$app->session->hasFlash("success")) : ?>

        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
            </button>
            <span>
                <?= Yii::$app->session->getFlash("success") ?>
            </span>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash("error")) : ?>

        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
            </button>
            <span> <?= Yii::$app->session->getFlash("error") ?>
            </span>
        </div>
    <?php endif; ?>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col-sm-12 col-lg-2">
            <ul class="nav nav-pills mb-3 pdt_ul" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-general-tab" data-toggle="pill" href="#pills-general" role="tab" aria-controls="pills-general" aria-selected="true">General</a>
                </li>
               
            </ul>
        </div>
        <div class="col-sm-12 col-lg-10">

            <div class="clearfix">
                <?= $form->errorSummary($model); ?>
                <?php // $form->errorSummary($product_attribute); ?>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <!--<div class="tab-pane fade show active" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab">-->
                <div class="tab-pane fade  show active box_item" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab">

                    <div class="row">
                        <?php
                        $merchant = [];
                        if (\Yii::$app->user->identity->interface == 'merchant') {
                            $get_merchants = \common\models\Merchant::find()->where(['status' => 10, 'id' => \Yii::$app->user->identity->id])->all();
                            $model->merchant_id = \Yii::$app->user->identity->id;
                            $disable = TRUE;
                        } else if (\Yii::$app->user->identity->interface == 'franchise') {
                            $disable = FALSE;
                            $get_merchants = \common\models\Merchant::find()->where(['franchise_id' => \Yii::$app->user->identity->id])->all();
                        } else {
                            $disable = FALSE;
                            $get_merchants = \common\models\Merchant::find()->where(['status' => 10])->all();
                        }
                        if ($get_merchants != NULL) {
                            foreach ($get_merchants as $get_merchant) {
                                $merchant[$get_merchant->id] = $get_merchant->business_name . '(' . $get_merchant->email . ')';

                                //                        $merchant[$get_merchant->id] = $get_merchant->first_name . ' ' . $get_merchant->last_name . '(' . $get_merchant->country0->country_name . ')';
                            }
                        }
                        ?>

                        <?php
                        $discount = [];
                        $get_discounts = [];
                        if (!$model->isNewRecord) {
                        } else {
                            $get_discounts = \common\models\Discounts::find()->where(['status' => 1, 'item_type' => 2])->andWhere("merchant_id IS NULL")->all();
                        }
                        /*  if ($get_discounts != NULL) {
                            foreach ($get_discounts as $get_discount) {
                                $discount[$get_discount->id] = $get_discount->title . ' - ' . ($get_discount->discount_type == 1 ? "Flat (" . $get_discount->discount_rate . ") " : " Percantage (" . $get_discount->discount_rate . "%)");
                            }
                        } */
                        ?>
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'package_title')->textInput(['maxlength' => true]) ?>
                               
                            </div>
                        </div>
                    <?php /*    <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                
                                <?php $form->field($model, 'category_id')->textInput(['maxlength' => true])->label('Category') ?>
                            </div>
                        </div> */?>
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">

                                <label class="control-label" for="productsservices-category_id">Category
                                </label>
                                <!-- <i class="fa fa-plus add_cat"></i> -->
                                <?php //Pjax::begin(['id' => 'product_service']) ?>
                               <?php
                                $options = array();

                                $datas = \common\models\Category::find()->all();
                                if ($datas != NULL) {
                                    foreach ($datas as $data) {
                                        if (!empty($data)) {
                                            $option_items = Yii::$app->SelectCategory->selectCategories($data);
                                            $option_data = explode('-', $option_items);
                                            $option_data_array = array_reverse($option_data);
                                            $latest_option = [];
                                            if ($option_data_array != NULL) {
                                                foreach ($option_data_array as $option_data_arr) {
                                                    $option_cat = \common\models\Category::find()->where(['id' => $option_data_arr])->one();
                                                    $latest_option[] = $option_cat->category_name;
                                                }
                                            }
                                            $option_text = implode(' -> ', $latest_option);
                                            $options[$data->id] = $option_text;
                                        }
                                    }
                                }
                                ?>
                                <?php
                                echo Select2::widget([
                                    'model' => $model,
                                    'attribute' => 'category_id',
                                    'data' => $options,
                                    'theme' => Select2::THEME_MATERIAL,
                                    'options' => ['placeholder' => 'Select a  Category.', 'class' => 'change_category'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'tokenSeparators' => [',', ' '],
                                        'maximumInputLength' => 20
                                    ],
                                ]);
                                ?>
                                <?php //Pjax::end() ?>

                            </div>
                        </div>
                        <div class="col-sm-3">

                            <div class="form-group bmd-form-group">

                                <?= $form->field($model, 'overview')->textarea(['rows' => 1]) ?>
                            </div>
                        </div>
                        <div class="col-sm-3">

                            <div class="form-group bmd-form-group">

                                <?= $form->field($model, 'packaage_organize')->textarea(['rows' => 1]) ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'short_description_en')->widget(CKEditor::className(), [
                                    'options' => ['rows' => 6],
                                    'preset' => 'custom'
                                ]) ?>
                             
                            </div>
                        </div>

                        <div class="col-sm-6">

                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'long_description_en')->widget(CKEditor::className(), [
                                    'options' => ['rows' => 6],
                                    'preset' => 'custom'
                                ]) ?>

                            </div>
                        </div>
                        <div class="col-sm-12">

                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'terms_condition')->widget(CKEditor::className(), [
                                    'options' => ['rows' => 6],
                                    'preset' => 'custom'
                                ]) ?>

                            </div>
                        </div>
                        
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>

                            </div>
                        </div>
                        
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'destination')->dropDownList(ArrayHelper::map(\common\models\PackageDestination::find()->all(), 'id', 'title'), ['prompt' => 'Choose a Destination ', 'class' => 'form-control checking attribute attr_name', 'id' => 'abcd']) ?>

                            </div>
                        </div>


                    </div>

                    <div class="row">

                    <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <div id="imagePriview">
                    <?php
                    if (isset($model->id) && $model->id > 0 && isset($model->banner_image) && $model->banner_image !== "") {
                        $imgPath = ((yii\helpers\Url::base())) . '/../uploads/product-banner/' . $model->id . '/image/' . $model->banner_image;
                    } else {
                        $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                    }
                    echo '<img width="125" style="border: 2px solid #d2d2d2;" src="' . $imgPath . '" />';
                    ?>
                </div>
                <br />
                <?php
                echo '<label class="control-label">Upload Banner Image</label>';
                echo FileInput::widget([
                    'model' => $model,
                    'attribute' => 'banner_image',
                    'options' => [
                        //                        'multiple' => true
                        'id' => 'input-6',
                    ],
                    'pluginOptions' => [
                        'showUpload' => false,
                        'allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
                    ]
                ]);
                ?>
                <span class="bmd-help"><?= Html::activeHint($model, 'banner_image'); ?></span>
            </div>
        </div>
                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                                <div id="imagePriview">
                                    <?php
                                    $imgPath = Yii::$app->ManageRequest->getImage($model);

                                    echo '<img width="125" style="border: 2px solid #d2d2d2;" src="' . $imgPath . '" />';
                                    ?>
                                </div>
                                <br />
                                <?php
                                echo '<label class="control-label">Upload Thumbonile Image</label>';
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
                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                                <div id="imagePriview">
                                    <?php
                                    $images = explode(',', $model->gallery);
                                    $result_html = '';
                                    if ($images != NULL) {
                                        foreach ($images as $image) {
                                            $delete_url = Yii::$app->request->baseUrl . '/products/products-services/gallery-delete?id=' . $model->id . '&item=' . $image;
                                            if (isset($model->id) && $model->id > 0 && isset($model->gallery) && $model->gallery !== "") {
                                                $imgPath = ((yii\helpers\Url::base())) . '/../uploads/products/' . base64_encode($model->id) . '/gallery/' . $image;
                                                $result_html .= '<div class ="img_gallery">                    <a href="' . $imgPath . '" class="thumbnail"><img src="' . $imgPath . '" alt="Image Alt" /></a>
                                                    <a href="' . $delete_url . '"><i class="fa fa-trash trash_file"></i></a></div>';
                                                                                                } else {
                                                                                                    $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                                                                                                    $result_html .= '<div class ="img_gallery">                    <a href="' . $imgPath . '" class="thumbnail"><img src="' . $imgPath . '" alt="Image Alt" /></a>
                                                    </div>';
                                            }
                                        }
                                        echo $result_html;
                                    }
                                    ?>
                                    <div class="clearfix"></div>
                                </div>
                                <br />
                                <?php
                                echo '<label class="control-label">Upload  Gallery</label>';
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
                                        'maxFileCount' => 5,
                                        'maxFileSize' => 5000
                                    ]
                                ]);
                                ?>
                                <span class="bmd-help"><?= Html::activeHint($model, 'gallery'); ?></span>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer ml-auto mr-auto">

                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="temp_name">

    </div>
    <div class="temp_value">

    </div>
    <script>


    </script>
    <?php
    $this->registerJs(
        <<< EOT_JS_CODE


EOT_JS_CODE
    );
    ?>