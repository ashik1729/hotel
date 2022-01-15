<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Events */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$franchise = [];

$get_franchises = \common\models\Franchise::find()->where(['status' => 10])->all();
if ($get_franchises != NULL) {
    foreach ($get_franchises as $get_franchise) {
        $franchise[$get_franchise->id] = $get_franchise->first_name . ' ' . $get_franchise->first_name . '(' . $get_franchise->country0->country_name . ')';
    }
}

$cities = [];
$get_cities_query = \common\models\City::find()->where(['status' => 1, 'country' => $model->country]);
$get_cities = $get_cities_query->all();
if ($get_cities != NULL) {
    foreach ($get_cities as $get_city) {
        $cities[$get_city->id] = $get_city->name_en;
    }
}
?>
<div class="card-body events-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">


        <div class="col-sm-4">

            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'store_id')->dropDownList($franchise, ['prompt' => 'Choose A Store', 'class' => 'form-control ']); ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'title_ar')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'description_en')->textarea(['rows' => 6]) ?>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'description_ar')->textarea(['rows' => 6]) ?>

            </div>
        </div>
        <?php
        if (!$model->isNewRecord) {
            $model->date_time = date('Y-m-d', strtotime($model->date_time)) . "T" . date('H:i', strtotime($model->date_time));
        }
        ?>
        <div class="col-sm-4">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'date_time')->textInput(['type' => 'datetime-local']) ?>

            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Yes', '0' => 'No'], ['prompt' => 'Select Status']) ?>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'sort_order')->textInput(['value' => '0']) ?>

            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'country')->dropDownList(ArrayHelper::map(\common\models\Country::find()->all(), 'id', 'country_name'), ['prompt' => '', 'class' => 'form-control select_event_country']); ?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'city')->dropDownList($cities, ['prompt' => 'Select City', 'class' => 'form-control select_city']); ?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'place')->textInput() ?>

            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'place_ar')->textInput() ?>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group bmd-form-group">
                <div id="imagePriview">
                    <?php
                    if (isset($model->id) && $model->id > 0 && isset($model->file) && $model->file !== "") {
                        $imgPath = ((yii\helpers\Url::base())) . '/../uploads/events/' . $model->id . '/file/' . $model->file;
                        echo '<a target="new" href="' . $imgPath . '">View File</a>';
                    }
                    ?>
                </div>
                <br/>
                <?php
                echo '<label class="control-label">Upload File (Image/Video)</label>';
                echo FileInput::widget([
                    'model' => $model,
                    'attribute' => 'file',
                    'options' => [
//                        'multiple' => true
                        'id' => 'input-2',
                    ],
                    'pluginOptions' => [
                        'showUpload' => false,
                        'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'MP4', 'MPEG-4', 'gif'],
                    ],
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
                            if (isset($model->id) && $model->id > 0 && isset($model->gallery) && $model->gallery !== "") {
                                $imgPath = ((yii\helpers\Url::base())) . '/../uploads/events/' . $model->id . '/gallery/' . $image;
                            } else {
                                $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                            }
                            $delete_url = Yii::$app->request->baseUrl . '/events/gallery-delete?id=' . $model->id . '&item=' . $image;
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
                        'showPreview' => true,
                        'showCaption' => false,
                        'showRemove' => true,
                        'overwriteInitial' => false,
                        'maxFileSize' => 2800
                    ]
                ]);
                ?>
                <span class="bmd-help"><?= Html::activeHint($model, 'gallery'); ?></span>

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
        $(document).ready(function(){
          $(function () {
          //   $('#events-date_time').datetimepicker();
         });
        });


EOT_JS_CODE
);
?>