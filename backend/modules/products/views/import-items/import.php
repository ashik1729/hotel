<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MobileStrings */

$this->title = 'Import Error Code and Localaised String';
$this->params['breadcrumbs'][] = ['label' => 'Import Error Code and Localaised String', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="content mobile-strings-create">

    <div class="container-fluid">
        <div class="card ">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">account_box</i>
                </div>
                <h4 class="card-title">
                    <?= $model->isNewRecord ? ' Import Items' : 'Import Error Code and Localaised String'; ?>
                    <div class="pull-right">

                        <a class="btn btn-xs btn-success btn-round btn-fab" href="index" rel="tooltip" data-placement="bottom" data-original-title="Back"><b class="material-icons">keyboard_arrow_left</b><div class="ripple-container"></div></a>
                    </div>
                </h4>
            </div>

            <?php if (Yii::$app->session->hasFlash("success")): ?> 
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>

                    <?= Yii::$app->session->getFlash("success") ?>
                </div>
            <?php endif; ?>

            <?php if (Yii::$app->session->hasFlash("error")): ?>
                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>

                    <?= Yii::$app->session->getFlash("error") ?>
                </div>
            <?php endif; ?>
            <?=
            $this->render('_import', [
                'model' => $model,
            ])
            ?>
        </div>
    </div>
</div>