<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderStatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Order Statuses';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php  $model_path = str_replace(" ", "-", strtolower(Html::encode($this->title))) ?>


<div class="content order-status-index">
    <?php  $urll = str_replace(" ", "-", strtolower(Html::encode($this->title))) ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">account_box</i>
                        </div>
                        <h4 class="card-title">

                                                        <?= Html::encode($this->title) ?>
                            <div class="pull-right">


                                <a class="btn btn-xs btn-primary btn-round btn-fab" href="<?= \yii\helpers\Url::to(['create']);?>" rel="tooltip" data-placement="bottom" data-original-title="Add <?= Html::encode($this->title) ?>"><b class="material-icons">add</b></a>
                            </div>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="material-datatables">
                                                                                            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                            
                                                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
        'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                            'id',
            'name',
            'description:ntext',
            'created_at',
            'updated_at',
            //'created_by',
            //'updated_by',
            //'status',
            //'sort_order',

                                [
                                'header' => \Yii::t('app', 'Actions'),
                                'class' => '\yii\grid\ActionColumn',
                                'contentOptions' => [
                                'class' => 'table-actions'
                                ],
                                'template' => '{view} {update} {delete}',
                                'buttons' => [
                                'view' => function ($url, $model) {
                                return Html::a(
                                '<i class="fa fa-eye"></i>', \yii\helpers\Url::to(['view', 'id' => $model->id]), [
                                'rel' => "tooltip",
                                'data-original-title' => 'View this user',
                                'data-placement' => 'top',
                                'style' => 'margin-right: 10px'
                                ]
                                );
                                },
                                'update' => function ($url, $model) {
                                return Html::a(
                                '<i class="fa fa-edit"></i>', \yii\helpers\Url::to(['update', 'id' => $model->id]), [
                                'rel' => "tooltip",
                                'data-original-title' => 'Edit this user',
                                'data-placement' => 'top',
                                'style' => 'margin-right: 10px'
                                ]
                                );
                                },
                                'delete' => function ($url, $model) {
                                return Html::a(

                                '<i class="fa fa-trash-o"></i>', \yii\helpers\Url::to(['delete', 'id' => $model->id]), [
                                'rel' => "tooltip",
                                'data-original-title' => 'Delete this user?',
                                'data-placement' => 'top',
                                'data-pjax' => '0',
                                'data-confirm' => 'Are you sure you want to delete this item?',
                                'data-method' => 'post',
                                'style' => 'margin-right: 10px'
                                ]
                                );
                                },
                                ]
                                ],
                                ],
                                ]); ?>
                            
                            

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

