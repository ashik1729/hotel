<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\MarketingNotification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body marketing-notification-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">


        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'title_ar')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'description_en')->textarea(['rows' => 1]) ?>

            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'description_ar')->textarea(['rows' => 1]) ?>

            </div>
        </div>



        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable']) ?>

            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?php
                echo $form->field($model, 'notification_type')->dropDownList(ArrayHelper::map(\common\models\NotificationType::find()->all(), 'id', 'name'), ['class' => 'form-control', 'prompt' => 'Choose Notification Type']);
                ?>

            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?php
                echo $form->field($model, 'user_group')->dropDownList(ArrayHelper::map(\common\models\UserGroup::find()->all(), 'id', 'name'), ['class' => 'form-control', 'prompt' => 'Choose User Group']);
                ?>
            </div>
        </div>

        <div class="col-xs-2 col-sm-3">
            <label class="control-label" for="productsservices-merchant_id">Users
            </label>
            <?php
            $users = \common\models\User::find()->where('status = 10')->andWhere('user_type =1')->all();
            $selected = [];
            if ($model->id != NULL && $model->id != '') {
                if ($users != NULL) {
                    foreach ($users as $user) {
                        $use[$user->id] = $user->first_name . ' ' . $user->last_name . '(' . $user->email . ')';
                    }
                } else {
                    $use = [];
                }
                $exp_user = explode(',', $model->user);

                if ($exp_user != NULL) {
                    foreach ($exp_user as $exp) {
                        $selected[$exp] = array("selected" => true);
                    }
                }
            } else {
                if ($users != NULL) {
                    foreach ($users as $user) {
                        $use[$user->id] = $user->first_name . ' ' . $user->last_name . '(' . $user->email . ')';
                    }
                } else {
                    $use = [];
                }
            }
            echo Select2::widget([
                'model' => $model,
                'attribute' => 'user[]',
                'data' => $use,
//                'disabled' => $disable,
                'theme' => Select2::THEME_MATERIAL,
                'options' => ['placeholder' => 'Choose one or more users.', 'class' => 'user_change', 'multiple' => true],
                'pluginOptions' => [
                    'allowClear' => true,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 20
                ],
            ]);
            ?>

        </div>

        <div class="col-sm-3">
            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group bmd-form-group">
                <?php
                echo
                $form->field($model, 'file')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*'],
                    'pluginOptions' => ['previewFileType' => 'any',
                        'allowedFileExtensions' => ['png', 'jpeg', 'jpg'],
                        'showUpload' => false
                    ],
                ]);
                ?>

                <div class="form-group">
                    <div id="imagePriview">
                        <?php
                        if (isset($model->id) && $model->id > 0 && isset($model->file) && $model->file !== "") {

                            $imgPath = ((yii\helpers\Url::base())) . '/../uploads/marketing-notification/' . $model->id . '.' . $model->file;
                        } else {
                            $imgPath = ((yii\helpers\Url::base())) . '/../backend/web/img/no-image.jpg';
                        }

                        echo '<img width="125" style="border: 2px solid #d2d2d2;" src="' . $imgPath . '" />';
                        ?>
                    </div>

                </div>
            </div>
        </div>


    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
