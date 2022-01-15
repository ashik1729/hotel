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

    <?php if (Yii::$app->session->hasFlash("success")): ?>

        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
            </button>
            <span>
                <?= Yii::$app->session->getFlash("success") ?>
            </span>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash("error")): ?>

        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
            </button>
            <span>                <?= Yii::$app->session->getFlash("error") ?>
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
                <!--        <li class="nav-item">
                            <a class="nav-link" id="pills-gallery-tab" data-toggle="pill" href="#pills-gallery" role="tab" aria-controls="pills-gallery" aria-selected="false">Gallery</a>
                        </li>-->
                <li class="nav-item">
                    <a class="nav-link" id="pills-price-tab" data-toggle="pill" href="#pills-price" role="tab" aria-controls="pills-price" aria-selected="false">Price & Quantity</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-attributes-tab" data-toggle="pill" href="#pills-attributes" role="tab" aria-controls="pills-attributes" aria-selected="false">Attributes & Variation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-seo-tab" data-toggle="pill" href="#pills-seo" role="tab" aria-controls="pills-seo" aria-selected="false">SEO & Settings</a>
                </li>
            </ul>
        </div>
        <div class="col-sm-12 col-lg-10">

            <div class="clearfix">
                <?= $form->errorSummary($model); ?>
                <?= $form->errorSummary($product_attribute); ?>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <!--<div class="tab-pane fade show active" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab">-->
                <div class="tab-pane fade  show active box_item" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab">
                    <div class="col-sm-12">
                        <!--<hr/>-->

                        <ul class="nav nav-pills pills-language-tab" id="pills-language-tab" role="tablist">
                            <?php if ($languages != NULL) { ?>

                                <?php foreach ($languages as $key => $language) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?= $key == 0 ? 'active' : ''; ?>" id="pills-<?php echo $language->shortcode; ?>-tab" data-toggle="pill" href="#pills-<?php echo $language->shortcode; ?>" role="tab" aria-controls="pills-<?php echo $language->shortcode; ?>" aria-selected="true"><?php echo $language->name; ?></a>
                                    </li>
                                <?php } ?>
                            <?php } ?>

                        </ul>
                        <div class="tab-content  pills-language-content" id="pills-tab-content">

                            <?php if ($languages != NULL) { ?>
                                <?php foreach ($languages as $key => $language) { ?>


                                    <div class="tab-pane fade <?= $key == 0 ? 'show active' : ''; ?> " id="pills-<?php echo $language->shortcode; ?>" role="tabpanel" aria-labelledby="pills-<?php echo $language->shortcode; ?>-tab">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="form-group bmd-form-group">
                                                    <?= $form->field($model, 'product_name_' . strtolower($language->shortcode))->textInput(['maxlength' => true]) ?>

                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="form-group bmd-form-group">
                                                    <?= $form->field($model, 'short_description_' . strtolower($language->shortcode))->textarea(['rows' => 1]) ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="form-group bmd-form-group">
                                                    <?= $form->field($model, 'long_description_' . strtolower($language->shortcode))->textarea(['rows' => 1]) ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>



                            <hr/>

                        </div>

                    </div>

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
                            if (\Yii::$app->user->identity->interface == 'merchant') {
                                $get_discounts = \common\models\Discounts::find()->where(['status' => 1, 'item_type' => 2, 'merchant_id' => \Yii::$app->user->identity->id])->orWhere("merchant_id IS NULL")->all();
                                $disable = TRUE;
                            } else if (\Yii::$app->user->identity->interface == 'franchise') {
                                $get_merchant_list = common\models\Merchant::find()->select('id')->where(['status' => 10, 'franchise_id' => 3])->asArray()->all();
                                $get_merchant_final_list = array_column($get_merchant_list, 'id');
//                        $get_discounts = \common\models\Discounts::find()->where(['IN', 'merchant_id', $get_merchant_final_list])->orWhere("merchant_id IS NULL")->all();
                                $get_discounts = \common\models\Discounts::find()->where(['status' => 1, 'item_type' => 2])->andWhere(['merchant_id' => $model->merchant_id])->orWhere("merchant_id IS NULL")->all();
                            } else {
                                $get_discounts = \common\models\Discounts::find()->where(['status' => 1, 'item_type' => 2])->andWhere(['merchant_id' => $model->merchant_id])->orWhere("merchant_id IS NULL")->all();
//                        $get_discounts = \common\models\Discounts::find()->where(['status' => 1])->all();
                            }
                        } else {
                            $get_discounts = \common\models\Discounts::find()->where(['status' => 1, 'item_type' => 2])->andWhere("merchant_id IS NULL")->all();
                        }
                        if ($get_discounts != NULL) {
                            foreach ($get_discounts as $get_discount) {
                                $discount[$get_discount->id] = $get_discount->title . ' - ' . ($get_discount->discount_type == 1 ? "Flat (" . $get_discount->discount_rate . ") " : " Percantage (" . $get_discount->discount_rate . "%)");
                            }
                        }
                        ?>
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'type')->dropDownList(['1' => 'Product', '2' => 'Shop Service', '3' => 'Home Service']) ?>
                            </div>
                        </div>
                        <div class="col-sm-3">

                            <div class="form-group bmd-form-group">
                                <label class="control-label" for="productsservices-merchant_id">Merchant
                                </label>
                                <?php
                                echo Select2::widget([
                                    'model' => $model,
                                    'attribute' => 'merchant_id',
                                    'data' => $merchant,
                                    'disabled' => $disable,
                                    'theme' => Select2::THEME_MATERIAL,
                                    'options' => ['placeholder' => 'Select a  Merchant.', 'class' => 'merchant_change'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'tokenSeparators' => [',', ' '],
                                        'maximumInputLength' => 20
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">

                                <label class="control-label" for="productsservices-merchant_id">Category
                                </label>
                                <i class="fa fa-plus add_cat"></i>
                                <?php Pjax::begin(['id' => 'product_service']) ?>
                                <?php
                                //  $datas = [];
                                $options = array();
//                        if (!$model->isNewRecord) {
//                            $merchant = \common\models\Merchant::findOne(['id' => $model->merchant_id]);
//                            if ($merchant != NULL) {
//                                if ($merchant->category != NULL) {
//                                    $exp_category = explode(',', $merchant->category);
//                                    if ($exp_category != NULL) {
////                                        $get_categorys = \common\models\Category::find()->where(['IN', 'id', $exp_category])->all();
////                                        if ($get_categorys != NULL) {
////                                            foreach ($get_categorys as $get_category) {
////                                                $options[$get_category->id] = $get_category->category_name;
////                                            }
////                                        }
//                                    }
//                                }
//                            }
//                            $get_categorys = \common\models\Category::find()->where(['status' => 1])->all();
//                            if ($get_categorys != NULL) {
//                                foreach ($get_categorys as $get_category) {
//                                    $options[$get_category->id] = $get_category->category_name;
//                                }
//                            }

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
//                        }
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
                                <?php Pjax::end() ?>

                            </div>
                        </div>




                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>

                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'sort_order')->textInput() ?>

                            </div>
                        </div>

                        <?php
                        $get_products = common\models\ProductsServices::find()->where(['status' => 1])->all();
                        if ($model->isNewRecord) {
                            $search_data = [];
                            $related_products = [];
                            $related_products_value = [];
                        } else {
                            if ($model->search_tag != '') {
                                $search_datas = explode(',', $model->search_tag);
                                foreach ($search_datas as $search_dt) {
                                    $search_data[$search_dt] = $search_dt;
                                }
                            }
                            if ($model->related_products != '') {
                                $related_products_value = explode(',', $model->related_products);
                                foreach ($related_products as $related_product) {
                                    $related_products_value[$related_product] = $related_product;
                                }
                            }

                            if ($get_products != NULL) {
                                foreach ($get_products as $get_product) {
                                    $related_products[$get_product->id] = $get_product->product_name_en;
                                }
                            }
                        }
                        ?>
                        <div class="col-sm-3">

                            <div class="form-group bmd-form-group">
                                <label>Search Tags</label>
                                <?php
                                echo Select2::widget([
                                    'name' => 'search_tag',
                                    'value' => $search_data,
                                    'theme' => Select2::THEME_MATERIAL,
                                    'options' => ['placeholder' => 'Search Tags ...', 'multiple' => true],
                                    'maintainOrder' => true,
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'tags' => true,
                                        'tokenSeparators' => [','],
                                        'maximumInputLength' => 20
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-3">

                            <div class="form-group bmd-form-group">
                                <label>Related Products</label>

                                <?php
                                echo Select2::widget([
                                    'model' => $model,
                                    'attribute' => 'related_products',
                                    'data' => $related_products,
                                    'value' => $related_products_value, // initial value (will be ordered accordingly and pushed to the top)
                                    'theme' => Select2::THEME_MATERIAL,
                                    'options' => ['placeholder' => 'Select Related Products.', 'multiple' => true],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'tokenSeparators' => [',', ' '],
                                        'maximumInputLength' => 20
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>


                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'is_admin_approved')->dropDownList(['1' => 'Yes', '0' => 'No']) ?>

                            </div>
                        </div>




                    </div>

                    <div class="row">



                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                                <div id="imagePriview">
                                    <?php
                                    $imgPath = Yii::$app->ManageRequest->getImage($model);

                                    echo '<img width="125" style="border: 2px solid #d2d2d2;" src="' . $imgPath . '" />';
                                    ?>
                                </div>
                                <br/>
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
                                                $imgPath = ((yii\helpers\Url::base())) . '/../uploads/products/' . base64_encode($model->sku) . '/gallery/' . $image;
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
                                <br/>
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
                <!--        <div class="tab-pane fade" id="pills-gallery" role="tabpanel" aria-labelledby="pills-gallery-tab">


                        </div>-->
                <div class="tab-pane fade box_item" id="pills-price" role="tabpanel" aria-labelledby="pills-price-tab">



                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

                            </div>
                        </div>


                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'discount_id')->dropDownList($discount, ['prompt' => 'Choose a Coupon', 'class' => 'form-control discount_change'])->label("Coupon"); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'tax_applicable')->dropDownList(['1' => 'Yes', '0' => 'No'], ['prompt' => 'Choose']) ?>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'tax_amount')->dropDownList(ArrayHelper::map(\common\models\TaxClass::find()->all(), 'id', 'tax_name'), ['prompt' => 'Choose a Tax Slab', 'class' => 'form-control']); ?>

                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'stock_availability')->dropDownList(['1' => 'Yes', '0' => 'No']) ?>

                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'min_quantity')->textInput(['value' => 1, 'min' => 1]) ?>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'quantity')->textInput() ?>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'requires_shipping')->dropDownList(['1' => 'Yes', '0' => 'No']) ?>

                            </div>
                        </div>

                        <!--                        <div class="col-sm-3">
                                                    <div class="form-group bmd-form-group">
                        <?php //echo $form->field($model, 'new_from')->textInput(['type' => 'date']) ?>

                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group bmd-form-group">
                        <?php //echo $form->field($model, 'new_to')->textInput(['type' => 'date']) ?>

                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group bmd-form-group">
                        <?php //echo $form->field($model, 'sale_from')->textInput(['type' => 'date']) ?>

                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group bmd-form-group">
                        <?php //echo $form->field($model, 'sale_to')->textInput(['type' => 'date']) ?>

                                                    </div>
                                                </div>-->
                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?php echo $form->field($model, 'discount_type')->dropDownList(['1' => 'Flat Rate', '2' => 'Percentage'], ['prompt' => 'Select Discount Type']) ?>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?php echo $form->field($model, 'discount_rate')->textInput(['maxlength' => true]) ?>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?php echo $form->field($model, 'discount_from')->textInput(['type' => 'date']) ?>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?php echo $form->field($model, 'discount_to')->textInput(['type' => 'date']) ?>

                            </div>
                        </div>





                        <div class="col-sm-3">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'is_featured')->dropDownList(['1' => 'Yes', '0' => 'No']) ?>

                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'weight_class')->dropDownList(ArrayHelper::map(\common\models\WeightClass::find()->all(), 'id', 'name'), ['prompt' => 'Choose a Weight Class', 'class' => 'form-control']); ?>

                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade box_item" id="pills-attributes" role="tabpanel" aria-labelledby="pills-attributes-tab">
                    <?php Pjax::begin(['id' => 'attribute_section']) ?>
                    <?php
                    $get_product_attributes = \common\models\ProductAttributes::find()->where(['product_id' => $model->id])->all();
                    ?>
                    <div class="attribute_area">
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn_add_attr float-right mb-4 m-0" tabindex="0" type="button" ><b class="material-icons">add</b> Add New Attribute Value</button>
                                <button type="button" data-toggle="modal" data-target="#addattributeModal"  class="btn mr-3  float-right mb-4 add-attributes m-0" tabindex="0" type="button" ><b class="material-icons">add</b> Add  Attribute </button>
                            </div>
                        </div>
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
                                                        <a class="delete_attr_item btn btn-xs btn-danger btn-round btn-fab"  get_product_attribute="<?= $get_product_attribute->id; ?>" style="display:none" href="javascript:void(0)" >
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
                                                                <?= $form->field($product_attribute, 'attribute_id[attcnt][en]')->textInput(['lang' => 1, 'value' => $attr_list_data->name, 'class' => 'form-control mt-4 change_attr_name attr_en'])->label('English'); ?>
                                                                <?= $form->field($product_attribute, 'product_attributes_id[attcnt]')->hiddenInput(['value' => $get_product_attribute->id, 'class' => 'form-control '])->label(false); ?>
                                                                <div class="pop_over_content ">
                                                                    <div class="loader_wrapper ">
                                                                        <div class="loader">
                                                                        </div>
                                                                        <h4 class="text-center mt-2">  Loading suggestion...</h4>
                                                                    </div>

                                                                    <ul class="list-unstyled " role="listbox">

                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 attr_parent ar">
                                                                <?= $form->field($product_attribute, 'attribute_id[attcnt][ar]')->textInput(['lang' => 2, 'value' => $attr_list_data->name_ar, 'class' => 'form-control mt-4 change_attr_name attr_ar'])->label('عربي'); ?>
                                                                <div class="pop_over_content ">
                                                                    <div class="loader_wrapper ">
                                                                        <div class="loader">
                                                                        </div>
                                                                        <h4 class="text-center mt-2">  Loading suggestion...</h4>
                                                                    </div>
                                                                    <ul class="list-unstyled " role="listbox">
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4"></div>
                                                            <div class="col-sm-4 mt-3">
                                                                <label><input type="radio" <?= $pStatus; ?> id="productattributesvalue-price_status" name="ProductAttributesValue[price_status][attcnt]" value="1"> Price Applicable</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if ($attr_list_value_datas != NULL) {
                                                        ?>
                                                        <?php foreach ($attr_list_value_datas as $attr_list_value_data) { ?>
                                                            <div  class="attribute_value_item">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="attr_value_title">
                                                                            <h3>
                                                                                <span>   <?= $attr_list_value_data->attributesValue->value; ?></span>
                                                                                <a class="delete_attr_value btn btn-xs btn-danger btn-round btn-fab" product_attr_id="<?= $attr_list_value_data->id; ?>" style="display:none" href="javascript:void(0)" >
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
                                                                                    <div class="col-sm-4 attr_value_name">
                                                                                        <h5 class="attr_value_head"> Values</h5>
                                                                                    </div>
                                                                                    <div class="col-sm-4 attr_val_parent">
                                                                                        <div class="attr_value_data position-relative">
                                                                                            <?= $form->field($product_attribute, 'attribute_value[attcnt][en][]')->textInput(['lang' => 1, 'value' => $attr_list_value_data->attributesValue->value, 'class' => 'form-control mt-4 attr_value_en change_attr_value_name'])->label('English'); ?>
                                                                                            <?= $form->field($product_attribute, 'id[attcnt][]')->hiddenInput(['value' => $attr_list_value_data->id, 'class' => 'form-control '])->label(false); ?>

                                                                                            <div class="pop_over_content ">
                                                                                                <div class="loader_wrapper ">
                                                                                                    <div class="loader">
                                                                                                    </div>
                                                                                                    <h4 class="text-center mt-2">  Loading suggestion...</h4>
                                                                                                </div>

                                                                                                <ul class="list-unstyled " role="listbox">

                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-4 attr_val_parent ar">

                                                                                        <?= $form->field($product_attribute, 'attribute_value[attcnt][ar][]')->textInput(['lang' => 2, 'value' => $attr_list_value_data->attributesValue->value_ar, 'class' => 'form-control mt-4 attr_value_ar change_attr_value_name'])->label('عربي'); ?>
                                                                                        <div class="pop_over_content ">
                                                                                            <div class="loader_wrapper ">
                                                                                                <div class="loader">
                                                                                                </div>
                                                                                                <h4 class="text-center mt-2">  Loading suggestion...</h4>
                                                                                            </div>

                                                                                            <ul class="list-unstyled " role="listbox">

                                                                                            </ul>
                                                                                        </div>

                                                                                    </div>
                                                                                    <div class="col-sm-4"></div>
                                                                                    <div class="col-sm-4 mt-3">
                                                                                        <div class="attr_price">
                                                                                            <?= $form->field($product_attribute, 'price[attcnt][]')->textInput(['value' => $attr_list_value_data->price]) ?>

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-2 mt-3">
                                                                                        <?= $form->field($product_attribute, 'quantity[attcnt][]')->textInput(['value' => $attr_list_value_data->sort_order]) ?>

                                                                                    </div>
                                                                                    <div class="col-sm-2 mt-3">
                                                                                        <?= $form->field($product_attribute, 'sort_order[attcnt][]')->textInput(['value' => $attr_list_value_data->sort_order]) ?>

                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    <?php } ?>
                                                </div>
                                                <div class="attr_contents_btn_wrapper">
                                                    <button class="btn btn_add_attr_value" tabindex="0" type="button" ><b class="material-icons">add</b> Add New Attribute Value</button>
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
                                                <span>Attribute Name</span>
                                                <a class="delete_attr_item btn btn-xs btn-danger btn-round btn-fab" get_product_attribute="" style="display:none" href="javascript:void(0)" >
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
                                                        <h5 class="attr_head"> Name
                                                        </h5>
                                                    </div>
                                                    <div class="col-sm-4 attr_parent">
                                                        <?= $form->field($product_attribute, 'attribute_id[attcnt][en]')->textInput(['lang' => 1, 'value' => $attr_list_data->name, 'class' => 'form-control mt-4 change_attr_name attr_en'])->label('English'); ?>
                                                        <?= $form->field($product_attribute, 'product_attributes_id[attcnt]')->hiddenInput(['class' => 'form-control '])->label(false); ?>

                                                        <div class="pop_over_content ">
                                                            <div class="loader_wrapper ">
                                                                <div class="loader">
                                                                </div>
                                                                <h4 class="text-center mt-2">  Loading suggestion...</h4>
                                                            </div>
                                                            <ul class="list-unstyled " role="listbox">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 attr_parent ar">
                                                        <?= $form->field($product_attribute, 'attribute_id[attcnt][ar]')->textInput(['lang' => 2, 'value' => $attr_list_data->name_ar, 'class' => 'form-control mt-4 change_attr_name attr_ar'])->label('عربي'); ?>
                                                        <div class="pop_over_content ">
                                                            <div class="loader_wrapper ">
                                                                <div class="loader">
                                                                </div>
                                                                <h4 class="text-center mt-2">  Loading suggestion...</h4>
                                                            </div>
                                                            <ul class="list-unstyled " role="listbox">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4"></div>
                                                    <div class="col-sm-4 mt-3">
                                                        <label><input type="radio"  id="productattributesvalue-price_status" name="ProductAttributesValue[price_status][attcnt]" value="1"> Price Applicable</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="attribute_value_item">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="attr_value_title">
                                                            <h3>
                                                                <span> Attribute Value</span>
                                                                <a class="delete_attr_value btn btn-xs btn-danger btn-round btn-fab" product_attr_id="" style="display:none" href="javascript:void(0)" >
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
                                                                    <div class="col-sm-4 attr_value_name">
                                                                        <h5 class="attr_value_head"> Values</h5>
                                                                    </div>
                                                                    <div class="col-sm-4 attr_val_parent">
                                                                        <div class="attr_value_data position-relative">
                                                                            <?= $form->field($product_attribute, 'attribute_value[attcnt][en][]')->textInput(['lang' => 1, 'value' => $attr_list_value_data->attributesValue->value, 'class' => 'form-control mt-4 attr_value_en change_attr_value_name'])->label('English'); ?>
                                                                            <?= $form->field($product_attribute, 'id[attcnt][]')->hiddenInput(['class' => 'form-control '])->label(false); ?>

                                                                            <div class="pop_over_content ">
                                                                                <div class="loader_wrapper ">
                                                                                    <div class="loader">
                                                                                    </div>
                                                                                    <h4 class="text-center mt-2">  Loading suggestion...</h4>
                                                                                </div>
                                                                                <ul class="list-unstyled " role="listbox">
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4 attr_val_parent ar">
                                                                        <?= $form->field($product_attribute, 'attribute_value[attcnt][ar][]')->textInput(['lang' => 2, 'value' => $attr_list_value_data->attributesValue->value_ar, 'class' => 'form-control mt-4 attr_value_ar change_attr_value_name'])->label('عربي'); ?>
                                                                        <div class="pop_over_content ">
                                                                            <div class="loader_wrapper ">
                                                                                <div class="loader">
                                                                                </div>
                                                                                <h4 class="text-center mt-2">  Loading suggestion...</h4>
                                                                            </div>
                                                                            <ul class="list-unstyled " role="listbox">
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4"></div>
                                                                    <div class="col-sm-3 mt-3">
                                                                        <div class="attr_price">
                                                                            <?= $form->field($product_attribute, 'price[attcnt][]')->textInput(['value' => 0]) ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-2 mt-3">
                                                                        <?= $form->field($product_attribute, 'quantity[attcnt][]')->textInput(['value' => 1]) ?>
                                                                    </div>
                                                                    <div class="col-sm-3 mt-3">
                                                                        <?= $form->field($product_attribute, 'sort_order[attcnt][]')->textInput(['value' => 0]) ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="attr_contents_btn_wrapper">
                                            <button class="btn btn_add_attr_value" tabindex="0" type="button" ><b class="material-icons">add</b> Add New Attribute Value</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php Pjax::end()
                    ?>

                </div>


                <div class="tab-pane fade box_item" id="pills-seo" role="tabpanel" aria-labelledby="pills-seo-tab">

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'meta_title')->textarea(['rows' => 3]) ?>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'meta_description')->textarea(['rows' => 3]) ?>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group bmd-form-group">
                                <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 3]) ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer ml-auto mr-auto">

                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
                <?php $formattr = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                <table class="actions" style="display: none">
                    <tr>
                        <td>
                            <?= $formattr->field($product_attribute, 'attribute_id[]')->dropDownList(ArrayHelper::map(\common\models\Attributes::find()->all(), 'name', 'name'), ['prompt' => 'Choose a Attribute ', 'class' => 'form-control checking attribute attr_name', 'id' => 'abcd']) ?>
                            <div class="help-block"></div>


                        </td>

                        <td>
                            <?= $formattr->field($product_attribute, 'attributes_value_id[]')->dropDownList([], ['prompt' => 'Choose a Attribute Value', 'class' => 'form-control checking attribute attr_value', 'id' => 'efgh']) ?>


                        </td>

                        <td>
                            <?= $formattr->field($product_attribute, 'quantity')->textInput() ?>
                        </td>
                        <td>

                            <label><input type="radio" id="productattributesvalue-price_status"  name="ProductAttributesValue[price_status][]" value="1"> Price Applicable</label>
                        </td>
                        <td>

                            <?= $formattr->field($product_attribute, 'price')->textInput() ?>
                        </td>
                        <td>
                            <?= $formattr->field($product_attribute, 'sort_order')->textInput() ?>
                        </td>
                        <td>
                            <!--<a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>-->
                            <!--<a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>-->
                            <a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>




    <?php ActiveForm::end(); ?>

    <div class="temp_name">

    </div>
    <div class="temp_value">

    </div>
    <div class="modal fade" id="addcatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="cat_error"></p>
                    <form>
                        <?php $formone = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group bmd-form-group">

                                    <?php
                                    echo Select2::widget([
                                        'model' => $modelcat,
                                        'attribute' => 'parent',
                                        'data' => $options,
                                        'theme' => Select2::THEME_MATERIAL,
                                        'options' => ['placeholder' => 'Select a  Category.', 'class' => 'parentcat'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'tokenSeparators' => [',', ' '],
                                            'maximumInputLength' => 20
                                        ],
                                    ]);
                                    ?>
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group bmd-form-group">
                                    <?= $form->field($modelcat, 'category_name')->textInput(['maxlength' => true, 'class' => 'form-control  cat_name']) ?>
                                </div>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_cat">Save </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addattributeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Attributes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="attr_error"></p>
                    <form>
                        <?php $formattribute = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group bmd-form-group">

                                    <?php
                                    echo Select2::widget([
                                        'model' => $attribute,
                                        'attribute' => 'id',
                                        'data' => ArrayHelper::map(\common\models\Attributes::find()->where(['status' => 1])->all(), 'id', 'name'),
                                        'theme' => Select2::THEME_MATERIAL,
                                        'options' => ['placeholder' => 'Select a  Attributes.', 'class' => 'attribute add_attr_name'],
                                        'pluginOptions' => [
                                            'tags' => true,
                                            'closeOnSelect' => true,
                                            'allowClear' => true,
                                            'tokenSeparators' => [','],
                                            'maximumInputLength' => 40
                                        ],
                                    ]);
                                    ?>
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-sm-12">

                                <div class="form-group bmd-form-group">
                                    <label>Attribute Values</label>
                                    <?php
                                    echo Select2::widget([
                                        'name' => 'attr_value',
                                        'data' => [],
                                        'value' => [], // initial value (will be ordered accordingly and pushed to the top)
                                        'theme' => Select2::THEME_MATERIAL,
                                        'options' => ['placeholder' => 'Search Attribute Values ...', 'multiple' => true, 'class' => ' add_attr_value'],
                                        'maintainOrder' => true,
                                        'pluginOptions' => [
                                            'tags' => true,
                                            'allowClear' => true,
                                            'tokenSeparators' => [','],
                                            'maximumInputLength' => 20
                                        ],
                                    ]);
                                    ?>
                                    <div class="help-block"></div>

                                </div>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_attr">Save </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editAttributeValueModal" tabindex="-1" role="dialog" aria-labelledby="editAttributeValueModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAttributeValueModalTitle">Update Product Attributes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="attr_error"></p>
                    <form id="update_attr_form">
                        <?php $formattributeedit = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                        <div class="row update_attr_modal">


                            <div class="col-sm-6">
                                <div class="form-group bmd-form-group">
                                    <?= $formattributeedit->field($product_attribute, 'attribute_id')->dropDownList(ArrayHelper::map(\common\models\Attributes::find()->where(['status' => 1])->all(), 'id', 'name'), ['prompt' => 'Choose a Attribute', 'class' => 'form-control checking attribute_update update_attr_name', 'id' => 'input_update_attr']) ?>
                                    <?= $formattributeedit->field($product_attribute, 'id')->textInput(['id' => 'input_update_attr_id']) ?>
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group bmd-form-group">
                                    <?= $formattributeedit->field($product_attribute, 'attributes_value_id')->dropDownList([], ['prompt' => 'Choose a Attribute Value', 'class' => 'form-control checking attribute_update update_attr_value', 'id' => 'input_update_attr_value']) ?>

                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group bmd-form-group">
                                    <?= $formattributeedit->field($product_attribute, 'quantity')->textInput(['id' => 'input_update_attr_qty']) ?>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group bmd-form-group">
                                    <?= $formattributeedit->field($product_attribute, 'price')->textInput(['id' => 'input_update_attr_price']) ?>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group bmd-form-group">
                                    <?= $formattributeedit->field($product_attribute, 'price_status')->radio(['id' => 'input_update_attr_price_status']) ?>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group bmd-form-group">
                                    <?= $formattributeedit->field($product_attribute, 'sort_order')->textInput(['id' => 'input_update_attr_sort']) ?>
                                    <div class="help-block"></div>
                                </div>
                            </div>



                        </div>

                        <?php ActiveForm::end(); ?>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_update_attr">Save </button>
                </div>
            </div>
        </div>
    </div>
    <script>


    </script>
    <?php
    $this->registerJs(<<< EOT_JS_CODE


EOT_JS_CODE
    );
    ?>