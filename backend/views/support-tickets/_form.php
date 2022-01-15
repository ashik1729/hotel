<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\SupportTickets */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $producturl = \yii\helpers\Url::to(['/support-tickets/get-order-data']);
?>

<?php
$formatJs = <<< 'JS'
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
    }
    console.log(repo);
    var markup =
'<div class="row">' +
    '<div class="col-sm-3">' +

        '<b style="margin-left:5px">#' + repo.id + '</b>' +
    '</div>' +
    '<div class="col-sm-3">' + repo.text + '</div>' +
    '<div class="col-sm-3">' + repo.email + '</div>' +
    '<div class="col-sm-3">' + repo.date + '</div>' +
'</div>';
    return '<div style="overflow:hidden;">' + markup + '</div>';
};
var formatRepoSelection = function (repo) {
    return repo.id;
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
<div class="card-body support-tickets-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">




        <div class="col-sm-12 order_items">
            <div class="form-group bmd-form-group">
                <div class="col-sm-12">
                    <div class="form-group bmd-form-group">
                        <?php
                        echo $form->field($model, 'order_id')->widget(Select2::classname(), [
                            'options' => ['placeholder' => 'Search for a Order', 'class' => 'order_change'],
                            'theme' => Select2::THEME_MATERIAL,
                            'pluginOptions' => [
                                'allowClear' => true,
                                'initialize' => true,
                                'minimumInputLength' => 1,
                                'ajax' => [
                                    'url' => $producturl,
                                    'dataType' => 'json',
                                    'delay' => 250,
                                    'data' => new \yii\web\JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                                    'processResults' => new \yii\web\JsExpression($resultsJs),
                                    'cache' => true
                                ],
                                'escapeMarkup' => new \yii\web\JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new \yii\web\JsExpression('formatRepo'),
                                'templateSelection' => new \yii\web\JsExpression('formatRepoSelection'),
                            ],
                        ]);
                        ?>

                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'product_id')->dropDownList([], ['prompt' => 'Choose a country', 'class' => 'form-control product_data']); ?>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'message')->textInput() ?>

            </div>
        </div>



        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <div id="imagePriview">
                    <?php
                    if (isset($model->id) && $model->id > 0 && isset($model->image) && $model->image !== "") {
                        $imgPath = ((yii\helpers\Url::base())) . '/../uploads/language/' . $model->image;
                    } else {
                        $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                    }

                    echo '<a target="new" href="' . $imgPath . '" >View File</a>';
                    ?>
                </div>
                <br/>
                <?php
                echo '<label class="control-label">Upload Filw</label>';
                echo FileInput::widget([
                    'model' => $model,
                    'attribute' => 'file',
                    'options' => [
                        'id' => 'input-1',
//                        'multiple' => true
                    ],
                    'pluginOptions' => [
                        'showUpload' => false,
////                        'uploadUrl' => '/upload/create',
                        'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'docx', 'pdf'],
                    ]
                ]);
                ?>
                <span class="bmd-help"><?= Html::activeHint($model, 'image'); ?></span>

            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Pending', '2' => 'Open', '3' => 'Closed']); ?>

            </div>
        </div>


    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>


</script>
<?php
//Get User info based on change user dropdown
$this->registerJs(<<< EOT_JS_CODE
        $(document).on('change', '.order_change', function () {
            var order_id = $(this).val();

            $.ajax({
                url: basepath + "/support-tickets/get-order-info",
                type: "POST",
                data: {order_id: order_id},
                success: function (data) {
                    if (data != '') {
                        $('.product_data').html(data);
                    } else {
                        $('.product_data').html("");

                    }
                },
                error: function () {

                    md.showNotification('bottom', 'center', 'Something went wrong', '2');

                }

            });
        });
EOT_JS_CODE
);
?>