
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductsServices */

$this->title = 'Update Products Services: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Products Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>


<div class="content products-services-create">

    <div class="container-fluid">
        <div class="card ">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">account_box</i>
                </div>
                <h4 class="card-title">
                    <?= $model->isNewRecord ? 'Update Products Services' : 'Update Products Services'; ?>
                    <div class="pull-right">

                        <a class="btn btn-xs btn-success btn-round btn-fab" href="index" rel="tooltip" data-placement="bottom" data-original-title="Back"><b class="material-icons">keyboard_arrow_left</b><div class="ripple-container"></div></a>
                    </div>
                </h4>
            </div>

            <?=
            $this->render('_form', [
                'model' => $model,
                'modelcat' => $modelcat,
                'product_attribute' => $product_attribute,
                'attribute' => $attribute,
                'languages' => $languages,
            ])
            ?>
        </div>
    </div>
</div>