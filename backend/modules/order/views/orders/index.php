<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $model_path = str_replace(" ", "-", strtolower(Html::encode($this->title))) ?>


<div class="content orders-index">
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
                            <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>
                            <?php
                            $get_user = [];
                            $users = \common\models\User::find()->where(['status' => 10])->all();
                            if ($users != NULL) {
                                foreach ($users as $user) {
                                    $get_user[$user->id] = $user->first_name . ' ' . $user->last_name . '(' . $user->email . ')';
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
                                        'attribute' => 'user_id',
                                        'header' => 'user_id',
                                        'filter' => Html::activeDropDownList($searchModel, 'user_id', $get_user, ['class' => 'form-control ashik ', 'prompt' => 'All']),
                                        'value' => function($model) {

                                            return "<span style=' text-transform: capitalize'>" . $model->user->first_name . ' ' . $model->user->last_name . '(' . $model->user->email . ')' . "</span>";
                                        },
                                        'format' => 'html',
                                    ],
                                    [
                                        'attribute' => 'total_amount',
                                        'header' => 'total_amount',
                                        'value' => function($model) {

                                            return Yii::$app->Currency->convert($model->total_amount, $model->store);
                                        },
                                        'format' => 'html',
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'header' => 'Status',
                                        'filter' => Html::activeDropDownList($searchModel, 'status', ArrayHelper::map(\common\models\OrderStatus::find()->all(), 'id', 'name'), ['class' => 'form-control ashik ', 'prompt' => 'All']),
                                        'filterInputOptions' => ['class' => 'form-control ', 'id' => null, 'prompt' => 'All', 'data-live-search' => "true", 'title' => "Select a Status", 'data-hide-disabled' => "true"], // to change 'Todos' instead of the blank option
                                        'value' => function($data) {
                                            return $data->orderStatus->name;
                                        },
                                        'format' => 'html',
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'header' => 'Date',
                                        'value' => function($data) {
                                            return date('Y-m-d H:i A', strtotime($data->created_at));
                                        },
                                        'format' => 'html',
                                    ],
                                    [
                                        'attribute' => 'payment_status',
                                        'header' => 'Payment Status',
                                        'filter' => ['0' => 'Pending', '1' => 'Success', '2' => 'Failed'],
                                        'filterInputOptions' => ['class' => 'form-control ', 'id' => null, 'prompt' => 'All', 'data-live-search' => "true", 'title' => "Select a Status", 'data-hide-disabled' => "true"], // to change 'Todos' instead of the blank option
                                        'value' => function($data) {
                                            if ($data->payment_status == 2) {
                                                return "<span style='color:red;font-weight:bold'>Failed</span>";
                                            } else if ($data->payment_status == 1) {
                                                return "<span style='color:green;font-weight:bold'>Success</span>";
                                            } else {
                                                return "<span style='color:#ccc;font-weight:bold'>Pending</span>";
                                            }
                                        },
                                        'format' => 'html',
                                    ],
                                    'transaction_id',
//                                    'shipping_method',
//                                    'ship_address',
                                    //'bill_address',
                                    //'customer_comment:ntext',
                                    //'admin_comment:ntext',
                                    //'total_amount',
                                    //'payment_method',
                                    //'payment_status',
                                    //'status',
                                    //'created_at',
                                    //'created_by',
                                    //'updated_at',
                                    //'updated_by',
                                    //'shipping_charge',
                                    [
                                        'header' => \Yii::t('app', 'Actions'),
                                        'class' => '\yii\grid\ActionColumn',
                                        'contentOptions' => [
                                            'class' => 'table-actions'
                                        ],
                                        'template' => '{view}{delete}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                return Html::a(
                                                                '<i class="fa fa-eye"></i>', \yii\helpers\Url::to(['view', 'id' => $model->id]), [
//                                                            'rel' => "tooltip",
//                                                            'data-original-title' => 'View this user',
//                                                            'data-placement' => 'top',
//                                                            'style' => 'margin-right: 10px'
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

