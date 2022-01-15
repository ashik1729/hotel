<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserAdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Admins';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">account_box</i>
                        </div>
                        <h4 class="card-title">
                            Admin Users
                            <div class="pull-right">
                                <?=
                                Html::a(Html::tag('b', 'add', ['class' => 'material-icons']), ['create'], [
                                    'class' => 'btn btn-xs btn-primary btn-round btn-fab',
                                    'rel' => "tooltip",
                                    'data' => [
                                        'placement' => 'bottom',
                                        'original-title' => 'Add Admin User'
                                    ],
                                ])
                                ?>
                            </div>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="material-datatables">
                            <?php
                            Pjax::begin([
                                'enablePushState' => true,
                            ]);
                            ?>
                            <?=
                            GridView::widget([
                                'id' => 'users',
                                'tableOptions' => [
                                    'class' => 'table table-striped table-no-bordered table-hover',
                                ],
                                'options' => ['class' => 'table-responsive grid-view'],
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    'id',
                                    'first_name',
                                    'last_name',
                                    'username',
                                    'email:email',
                                    [
                                        'attribute' => 'role_id',
                                        'filter' => Html::activeDropDownList($searchModel, 'status', ArrayHelper::map(\common\models\AdminRole::find()->all(), 'id', 'role_name'), ['class' => 'form-control ashik ', 'prompt' => 'All']),
                                        //  'filter' => ArrayHelper::map(\common\models\Tower::find()->all(), 'code', 'code'),
                                        // 'filterInputOptions' => ['class' => 'form-control selectpicker', 'id' => null, 'prompt' => 'All', 'data-live-search' => "true", 'title' => "Select a tower", 'data-hide-disabled' => "true"], // to change 'Todos' instead of the blank option
                                        'value' => function($model) {
                                            return "<span style=' text-transform: capitalize'>" . $model->role->role_name . "</span>";
                                        },
                                        'format' => 'html',
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'header' => 'Admin Status',
                                        'filter' => ['0' => 'Disabled', '1' => 'Waiting for Account Verification', '10' => 'Active'],
                                        'filterInputOptions' => ['class' => 'form-control ', 'id' => null, 'prompt' => 'All', 'data-live-search' => "true", 'title' => "Select a Status", 'data-hide-disabled' => "true"], // to change 'Todos' instead of the blank option
                                        'value' => function($data) {
                                            if ($data->status == 1) {
                                                return "<span style='color:black;font-weight:bold'>Waiting for account verification</span>";
                                            } else if ($data->status == 10) {
                                                return "<span style='color:blue;font-weight:bold'>Active</span>";
                                            } else {
                                                return "Disable";
                                            }
                                        },
                                        'format' => 'html',
                                    ],
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
                                                                '<i class="fa fa-eye"></i>', \yii\helpers\Url::to(['/user-admin/view', 'id' => $model->id]), [
                                                            'rel' => "tooltip",
                                                            'data-original-title' => 'View this user',
                                                            'data-placement' => 'top',
                                                            'style' => 'margin-right: 10px'
                                                                ]
                                                );
                                            },
                                            'update' => function ($url, $model) {
                                                return Html::a(
                                                                '<i class="fa fa-edit"></i>', \yii\helpers\Url::to(['/user-admin/update', 'id' => $model->id]), [
                                                            'rel' => "tooltip",
                                                            'data-original-title' => 'Edit this user',
                                                            'data-placement' => 'top',
                                                            'style' => 'margin-right: 10px'
                                                                ]
                                                );
                                            },
                                            'delete' => function ($url, $model) {
                                                return Html::a(
                                                                '<i class="fa fa-trash-o"></i>', \yii\helpers\Url::to(['/user-admin/delete', 'id' => $model->id]), [
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
                            <?php Pjax::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
