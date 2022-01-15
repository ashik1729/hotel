<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SupportTicketsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Support Tickets';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $model_path = str_replace(" ", "-", strtolower(Html::encode($this->title))) ?>


<div class="content support-tickets-index">
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
                    <?php if (Yii::$app->session->hasFlash("success") != ""): ?>

                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span>
                                <?= Yii::$app->session->getFlash("success") ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <?php if (Yii::$app->session->hasFlash("error") != ""): ?>

                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span>                <?= Yii::$app->session->getFlash("error") ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="material-datatables">
                            <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>
                            <?php
                            $get_user = [];

                            $users = \common\models\User::find()->where(['status' => 10])->andWhere('user_type != 3')->all();
                            if ($users != NULL) {
                                foreach ($users as $user) {
                                    $purchase_status = \common\models\OrderProducts::find()->where(['user_id' => $user->id])->andWhere('status >= 2')->all();
                                    if ($purchase_status != NULL) {
                                        $get_user[$user->id] = $user->first_name . ' ' . $user->last_name . '(' . $user->email . ')';
                                    }
                                }
                            }

                            $get_admin = [];
                            $admins = \common\models\UserAdmin::find()->where(['status' => 10])->andWhere('role =1 OR role=5')->all();
                            if ($admins != NULL) {
                                foreach ($admins as $admin) {
                                    $get_admin[$admin->id] = $admin->first_name . ' ' . $admin->last_name . '(' . $admin->email . ')';
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
                                        'attribute' => 'admin_id',
                                        'header' => 'Asigned By',
                                        'filter' => Html::activeDropDownList($searchModel, 'admin_id', $get_admin, ['class' => 'form-control ashik ', 'prompt' => 'All']),
                                        'value' => function($model) {

                                            return "<span style=' text-transform: capitalize'>" . $model->admin->first_name . ' ' . $model->admin->last_name . '(' . $model->admin->email . ')' . "</span>";
                                        },
                                        'format' => 'html',
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'header' => 'Time',
                                        'value' => function($data) {
                                            if ($data->created_at != "") {
                                                return date('d M Y H:i A');
                                            } else {
                                                return "";
                                            }
                                        },
                                        'format' => 'html',
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'header' => 'Ticket Status',
                                        'filter' => ['1' => 'Pending', '2' => 'Open', '3' => 'Closed'],
                                        'filterInputOptions' => ['class' => 'form-control ', 'id' => null, 'prompt' => 'All', 'data-live-search' => "true", 'title' => "Select a Status", 'data-hide-disabled' => "true"], // to change 'Todos' instead of the blank option
                                        'value' => function($data) {
                                            if ($data->status == 1) {
                                                return "<span style='color:violet;font-weight:bold'>Pending</span>";
                                            } else if ($data->status == 2) {

                                                return "<span style='color:blue;font-weight:bold'>Open</span>";
                                            } else if ($data->status == 3) {

                                                return "<span style='color:green;font-weight:bold'>Closed</span>";
                                            }
                                        },
                                        'format' => 'html',
                                    ],
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
//                                            'update' => function ($url, $model) {
//                                                return Html::a(
//                                                                '<i class="fa fa-edit"></i>', \yii\helpers\Url::to(['update', 'id' => $model->id]), [
//                                                            'rel' => "tooltip",
//                                                            'data-original-title' => 'Edit this user',
//                                                            'data-placement' => 'top',
//                                                            'style' => 'margin-right: 10px'
//                                                                ]
//                                                );
//                                            },
//                                            'delete' => function ($url, $model) {
//                                                return Html::a(
//                                                                '<i class="fa fa-trash-o"></i>', \yii\helpers\Url::to(['delete', 'id' => $model->id]), [
//                                                            'rel' => "tooltip",
//                                                            'data-original-title' => 'Delete this user?',
//                                                            'data-placement' => 'top',
//                                                            'data-pjax' => '0',
//                                                            'data-confirm' => 'Are you sure you want to delete this item?',
//                                                            'data-method' => 'post',
//                                                            'style' => 'margin-right: 10px'
//                                                                ]
//                                                );
//                                            },
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

