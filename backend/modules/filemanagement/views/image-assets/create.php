<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ImageAssets */

$this->title = 'Create Image Assets';
$this->params['breadcrumbs'][] = ['label' => 'Image Assets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="content image-assets-create">

    <div class="container-fluid">
        <div class="card ">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">account_box</i>
                </div>
                <h4 class="card-title">
                    <?=  $model->isNewRecord ? 'Create Image Assets' : 'Update Image Assets';?>
                    <div class="pull-right">

                        <a class="btn btn-xs btn-success btn-round btn-fab" href="index" rel="tooltip" data-placement="bottom" data-original-title="Back"><b class="material-icons">keyboard_arrow_left</b><div class="ripple-container"></div></a>
                    </div>
                </h4>
            </div>

            <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
        </div>
    </div>
</div>