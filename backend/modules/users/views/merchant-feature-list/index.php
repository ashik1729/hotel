<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MerchantFeatureListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Merchant Feature Lists';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $model_path = str_replace(" ", "-", strtolower(Html::encode($this->title))) ?>


<div class="content merchant-feature-list-index">
    <?php $urll = str_replace(" ", "-", strtolower(Html::encode($this->title))) ?>
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


                                <a class="btn btn-xs btn-primary btn-round btn-fab" href="<?= \yii\helpers\Url::to(['create']); ?>" rel="tooltip" data-placement="bottom" data-original-title="Add <?= Html::encode($this->title) ?>"><b class="material-icons">add</b></a>
                            </div>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="material-datatables">
                            <?php
                            // echo $this->render('_search', ['model' => $searchModel]);
                            //
                            //

                            $merchantdatas = \common\models\Merchant::find()->where(['status' => 10])->all();
                            $merchant_list = [];
                            if ($merchantdatas != NULL) {
                                foreach ($merchantdatas as $data) {
                                    $merchant_list[$data->id] = $data->first_name . ' ' . $data->last_name . '(' . $data->email . ')';
                                }
                            }

                            $featurelist = [];

                            $get_feature_lists = \common\models\FeaturesList::find()->where(['status' => 1])->all();
                            if ($get_feature_lists != NULL) {
                                foreach ($get_feature_lists as $get_feature_list) {
                                    $featurelist[$get_feature_list->id] = $get_feature_list->title;
                                }
                            }
                            ?>

                            <?=
                            GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'id',
                                    [
                                        'attribute' => 'merchant_id',
                                        'header' => 'Merchants',
                                        'filter' => Html::activeDropDownList($searchModel, 'merchant_id', $merchant_list, ['class' => 'form-control ashik ', 'prompt' => 'All']),
                                        'value' => function($model) {
                                            return "<span style=' text-transform: capitalize'>" . $model->merchant->first_name . ' ' . $model->merchant->last_name . '(' . $model->merchant->email . ')' . "</span>";
                                        },
                                        'format' => 'html',
                                        'visible' => \Yii::$app->user->identity->interface != 'merchant' ? true : false
                                    ],
                                    [
                                        'attribute' => 'feature_id',
                                        'header' => 'Feature',
                                        'filter' => Html::activeDropDownList($searchModel, 'feature_id', $featurelist, ['class' => 'form-control ashik ', 'prompt' => 'All']),
                                        'value' => function($model) {
                                            return "<span style=' text-transform: capitalize'>" . $model->featureList->title . "</span>";
                                        },
                                        'format' => 'html',
                                    ],
                                    'value_en',
//                                    'value_ar',
//                                    'status',
                                    [
                                        'attribute' => 'status',
                                        'header' => 'Status',
                                        'filter' => ['0' => 'Disabled', '1' => 'Enable'],
                                        'filterInputOptions' => ['class' => 'form-control ', 'id' => null, 'prompt' => 'All', 'data-live-search' => "true", 'title' => "Select a Status", 'data-hide-disabled' => "true"], // to change 'Todos' instead of the blank option
                                        'value' => function($data) {
                                            if ($data->status == 1) {
                                                return "<span style='color:green;font-weight:bold'>Enable</span>";
                                            } else {
                                                return "<span style='color:red;font-weight:bold'>Disable</span>";
                                            }
                                        },
                                        'format' => 'html',
                                    ],
                                    //'created_at',
                                    //'updated_at',
                                    //'created_by',
                                    //'updated_by',
                                    //'created_by_type',
                                    //'updated_by_type',
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
                            ]);
                            ?>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

