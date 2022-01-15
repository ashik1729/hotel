<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AdminRoleList */

$this->title = 'Create Admin Role List';
$this->params['breadcrumbs'][] = ['label' => 'Admin Role Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="content admin-role-list-create">

    <div class="container-fluid">
        <div class="card ">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">account_box</i>
                </div>
                <h4 class="card-title">
                    <?=  $model->isNewRecord ? 'Create Admin Role List' : 'Update Admin Role List';?>
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