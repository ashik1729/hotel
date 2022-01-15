<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $model_path = str_replace(" ", "-", strtolower(Html::encode($this->title))) ?>


<div class="content user-index">
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


                                <!--<a class="btn btn-xs btn-primary btn-round btn-fab" href="<?= \yii\helpers\Url::to(['create']); ?>" rel="tooltip" data-placement="bottom" data-original-title="Add <?= Html::encode($this->title) ?>"><b class="material-icons">add</b></a>-->
                            </div>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="material-datatables">
                            <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

                            <?=
                            GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'id',
                                    'first_name',
                                    'last_name',
                                    'email',
                                    [
                                        'attribute' => 'status',
                                        'header' => 'Status',
                                        'filter' => ['0' => 'Disabled', '10' => 'Enable'],
                                        'filterInputOptions' => ['class' => 'form-control ', 'id' => null, 'prompt' => 'All', 'data-live-search' => "true", 'title' => "Select a Status", 'data-hide-disabled' => "true"], // to change 'Todos' instead of the blank option
                                        'value' => function($data) {
                                            if ($data->status == 10) {
                                                return "<span style='color:green;font-weight:bold'>Enable</span>";
                                            } else if ($data->status == 1) {

                                                return "<span style='color:red;font-weight:bold'>Not verified</span>";
                                            } else {
                                                return "<span style='color:red;font-weight:bold'>Disable</span>";
                                            }
                                        },
                                        'format' => 'html',
                                    ],
                                    [
                                        'attribute' => 'user_type',
                                        'header' => 'User Type',
                                        'filter' => ['1' => 'User', '2' => 'Merchants'],
                                        'filterInputOptions' => ['class' => 'form-control ', 'id' => null, 'prompt' => 'All', 'data-live-search' => "true", 'title' => "Select a Status", 'data-hide-disabled' => "true"], // to change 'Todos' instead of the blank option
                                        'value' => function($data) {
                                            if ($data->user_type == 1) {
                                                return "<span style='color:green;font-weight:bold'>User</span>";
                                            } else if ($data->status == 2) {

                                                return "<span style='color:blue;font-weight:bold'>Merchant</span>";
                                            } else if ($data->status == 2) {

                                                return "<span style='color:violet;font-weight:bold'>Guest User</span>";
                                            }
                                        },
                                        'format' => 'html',
                                    ],
                                    [
                                        'attribute' => 'account_type',
                                        'header' => 'Account Type',
                                        'filter' => ['1' => 'Email', '2' => 'Facebook', '3' => 'Gmail'],
                                        'filterInputOptions' => ['class' => 'form-control ', 'id' => null, 'prompt' => 'All', 'data-live-search' => "true", 'title' => "Select a Status", 'data-hide-disabled' => "true"], // to change 'Todos' instead of the blank option
                                        'value' => function($data) {
                                            if ($data->user_type == 1) {
                                                return "<span style='color:green;font-weight:bold'>Email</span>";
                                            } else if ($data->status == 2) {

                                                return "<span style='color:blue;font-weight:bold'>Face Book</span>";
                                            } else if ($data->status == 3) {

                                                return "<span style='color:orange;font-weight:bold'>Gmail</span>";
                                            }
                                        },
                                        'format' => 'html',
                                    ],
                                    'password_reset_token',
//                                    'gender',
//                                    'dob',
                                    //'email:email',
                                    //'password',
                                    //'password_reset_token',
                                    //'profile_image',
                                    //'mobile_number',
                                    //'address:ntext',
                                    //'country',
                                    //'state',
                                    //'city',
                                    //'auth_key',
                                    //'created_at',
                                    //'updated_at',
                                    //'status',
                                    //'newsletter',
                                    //'user_otp',
                                    //'emailverify:email',
                                    //'created_by',
                                    //'created_by_type',
                                    //'updated_by',
                                    //'updated_by_type',
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

