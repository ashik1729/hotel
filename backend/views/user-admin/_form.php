<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\UserAdmin */
/* @var $form yii\widgets\ActiveForm */
?>




<div class="content">
    <div class="container-fluid">
        <div class="card ">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">account_box</i>
                </div>
                <h4 class="card-title">
                    <?= $model->isNewRecord ? \Yii::t('app', 'Create User Admin') : \Yii::t('app', 'Update User') ?>
                    <div class="pull-right">
                        <?=
                        Html::a(Html::tag('b', 'keyboard_arrow_left', ['class' => 'material-icons']), ['index'], [
                            'class' => 'btn btn-xs btn-success btn-round btn-fab',
                            'rel' => "tooltip",
                            'data' => [
                                'placement' => 'bottom',
                                'original-title' => 'Back'
                            ],
                        ])
                        ?>
                    </div>
                </h4>
            </div>
            <div class="card-body">
                <?php
                $form = ActiveForm::begin([
                            'fieldConfig' => [
                                'template' => "{input} {error}",
                            ]
                ]);
                ?>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group bmd-form-group">
                            <label for="<?= Html::getInputId($model, 'first_name'); ?>" class="bmd-label-floating"><?= Html::activeLabel($model, 'first_name'); ?></label>
                            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true])->label(false); ?>
                            <span class="bmd-help"><?= Html::activeHint($model, 'first_name'); ?></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group bmd-form-group">
                            <label for="<?= Html::getInputId($model, 'last_name'); ?>" class="bmd-label-floating"><?= Html::activeLabel($model, 'last_name'); ?></label>
                            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true])->label(false); ?>
                            <span class="bmd-help"><?= Html::activeHint($model, 'last_name'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group bmd-form-group">
                            <label for="<?= Html::getInputId($model, 'email'); ?>" class="bmd-label-floating"><?= Html::activeLabel($model, 'email'); ?></label>
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true])->label(false); ?>
                            <span class="bmd-help"><?= Html::activeHint($model, 'email'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group bmd-form-group">
                            <label for="<?= Html::getInputId($model, 'role'); ?>" class="bmd-label-floating"><?= Html::activeLabel($model, 'role'); ?></label>
                            <?= $form->field($model, 'role')->textInput(['maxlength' => true])->label(false); ?>
                            <span class="bmd-help"><?= Html::activeHint($model, 'role'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group bmd-form-group">
                            <?= $form->field($model, 'status')->dropDownList(['1' => 'Enable', '0' => 'Disable'], ['prompt' => '', 'class' => 'form-control']); ?>

                            <span class="bmd-help"><?= Html::activeHint($model, 'status'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        echo '<label class="control-label">Tag Multiple</label>';
                        echo Select2::widget([
                            'name' => 'UserAdmin[role]',
                            'value' => [], // initial value
                            'data' => $data,
                            'maintainOrder' => true,
                            'toggleAllSettings' => [
                                'selectLabel' => '<i class="fas fa-check-circle"></i> Tag All',
                                'unselectLabel' => '<i class="fas fa-times-circle"></i> Untag All',
                                'selectOptions' => ['class' => 'text-success'],
                                'unselectOptions' => ['class' => 'text-danger'],
                            ],
                            'options' => ['placeholder' => 'Select a color ...', 'multiple' => true],
                            'pluginOptions' => [
                                'tags' => true,
                                'maximumInputLength' => 10
                            ],
                        ]);
                        ?>
                    </div>
                </div>
                <div class="card-footer ml-auto mr-auto">
                    <?= Html::submitButton($model->isNewRecord ? \Yii::t('app', 'Create') : \Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>

        </div>
    </div>
</div>