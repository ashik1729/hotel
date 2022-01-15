<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Modal;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php
    $datas = \common\models\Category::find()->all();
    $options = array();
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

    <div class="row">


        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'parent')->dropDownList($options, ['prompt' => 'Select Category']);
                ?>


                <?php
                echo Select2::widget([
                    'model' => $model,
                    'attribute' => 'parent',
                    'data' => $options,
                    'theme' => Select2::THEME_MATERIAL,
                    'options' => ['placeholder' => 'Select a Parent Category.'],
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
                <?= $form->field($model, 'category_name')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'category_name_ar')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'sort_order')->textInput() ?>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'header_visibility')->dropDownList(['1' => 'Yes', '0' => 'No']) ?>
            </div>
        </div>


        <!--        <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
        <?php
//                $form->field($model, 'description')->widget(CKEditor::className(), [
//                    'options' => ['rows' => 6],
//                    'preset' => 'basic'
//                ])
        ?>
                    </div>
                </div>-->
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'description_ar')->textarea(['rows' => 6]) ?>

            </div>
        </div>


        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <div id="imagePriview">
                    <?php
                    if (isset($model->id) && $model->id > 0 && isset($model->image) && $model->image !== "") {
                        $imgPath = ((yii\helpers\Url::base())) . '/../uploads/category/' . $model->id . '/image/' . $model->image;
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
                                $imgPath = ((yii\helpers\Url::base())) . '/../uploads/category/' . $model->id . '/gallery/' . $image;
                            } else {
                                $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                            }
                            $delete_url = Yii::$app->request->baseUrl . '/category/gallery-delete?id=' . $model->id . '&item=' . $image;
                            $result_html .= '<div class ="img_gallery"><a href="' . $delete_url . '"><img width="125" style="border: 2px solid #d2d2d2;" src="' . $imgPath . '" /><i class="fa fa-trash trash_file"></i></a></div>';
                        }
                        echo $result_html;
                    }
                    ?>
                    <div class="clearfix"></div>
                </div>
                <br/>
                <?php
                echo '<label class="control-label">Upload Business Gallery (If any)</label>';
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
        <?php
        if ($model->isNewRecord) {
            $search_data = [];
        } else {
            if ($model->search_tag != '') {
                $search_datas = explode(',', $model->search_tag);
                foreach ($search_datas as $search_dt) {
                    $search_data[$search_dt] = $search_dt;
                }
            }
        }
//        echo '<pre/>';
//        print_r($search_data);
        ?>
        <div class="col-sm-6">

            <div class="form-group bmd-form-group">
                <label>Search Tags</label>
                <?php
                echo Select2::widget([
//                    'model' => $model,
//                    'attribute' => 'search_tag',
                    'name' => 'search_tag',
                    'data' => $search_data,
                    'value' => $search_data, // initial value (will be ordered accordingly and pushed to the top)
                    'theme' => Select2::THEME_MATERIAL,
                    'options' => ['placeholder' => 'Select a state ...', 'multiple' => true],
                    'maintainOrder' => true,
                    'pluginOptions' => [
                        'allowClear' => true,
                        'tags' => true,
                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 20
                    ],
                ]);
                ?>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>
            </div>
        </div>


        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'meta_description')->textarea(['rows' => 6]) ?>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 6]) ?>

            </div>
        </div>
    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
