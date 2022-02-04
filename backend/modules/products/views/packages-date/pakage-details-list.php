<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PackagesDateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Packages Dates';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php  $model_path = str_replace(" ", "-", strtolower(Html::encode($this->title))) ?>


<div class="content packages-date-index">
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
                            <?php $pkg_id = isset($_GET['id'])?$_GET['id']:"0"?>

                                <a class="btn btn-xs btn-primary btn-round btn-fab" href="<?= \yii\helpers\Url::to(['create?pkg_id='.$pkg_id]);?>" rel="tooltip" data-placement="bottom" data-original-title="Add <?= Html::encode($this->title) ?>"><b class="material-icons">add</b></a>
                            </div>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="material-datatables">                            
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                        [
                                        'attribute' => 'package_date_id',
                                        'header' => 'Pckage Date',
                                        'value' => function($model) {
                                            $pkg_date = \common\models\PackagesDate::find()->where(['id' => $model->package_date_id])->one();
                                            if(!empty($pkg_date)) {
                                            return $pkg_date = date("Y-m-d",strtotime($pkg_date->package_date));

                                            }

                                        }
                                    ],
                                'package_id',
                                'min_person',
                                'max_person',
                                'price',
                                [
                                'header' => \Yii::t('app', 'Actions'),
                                'class' => '\yii\grid\ActionColumn',
                                'contentOptions' => [
                                'class' => 'table-actions'
                                ],
                                    'template' => '{update} {delete}',
                                    'buttons' => [
                                    
                                        'update' => function ($url, $model) {
                                        return Html::a(
                                        '<i class="fa fa-edit"></i>', \yii\helpers\Url::to(['update', 'pkgDateId' => $model->	package_date_id,'pkgPrcId' => $model->id]), [
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

